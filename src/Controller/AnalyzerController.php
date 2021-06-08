<?php

namespace Drupal\sentiment\Controller;

/**
 * @file
 * Contains \Drupal\sentiment\Controller\AnalyzerController.
 */


use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Sentiment\Analyzer;

/**
 * Analyzer main class.
 */
class AnalyzerController extends ControllerBase {
  /**
   * Configuration state Drupal Site.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Serialization service.
   *
   * @var \Drupal\Component\Serialization\Json
   */
  protected $serialization;

  /**
   * Form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;


  /**
   * Module Handler Service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Detoxify Connector.
   *
   * @var \Drupal\sentimental\Controller\DetoxifyConnector
   */

  /**
   * Perspective Connector.
   *
   * @var \Drupal\sentimental\Controller\DetoxifyConnector
   */
  protected $detoxify;

  /**
   * Construct method.
   */
  public function __construct(
    ConfigFactory $configFactory,
    Json $serialization,
    FormBuilderInterface $form_builder,
    $moduleHandler,
    EntityTypeManagerInterface $entityTypeManager,
    DetoxifyConnector $detoxify,
    PerspectiveConnector $perspective) {
    $this->configFactory = $configFactory;
    $this->serialization = $serialization;
    $this->formBuilder = $form_builder;
    $this->moduleHandler = $moduleHandler;
    $this->entityTypeManager = $entityTypeManager;
    $this->detoxify = $detoxify;
    $this->perspective = $perspective;
  }

  /**
   * Create method.
   */
  public static function create(ContainerInterface $container) {
    // SET DEPENDENCY INJECTION.
    return new static(
      $container->get('config.factory'),
      $container->get('serialization.json'),
      $container->get('form_builder'),
      $container->get('module_handler'),
      $container->get('entity_type.manager'),
      $container->get('sentiment.detoxify'),
      $container->get('sentiment.perspective')
    );
  }

  /**
   * Main method. Get mealplan render array.
   *
   * @param string $str_date
   *   Date in Y-m-d format. if empty now will be used.
   * @param string $nojs
   *   If set to ajax it will perform an ajax load.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   *
   * @return array
   *   Mealplan render array.
   */
  public function analyzer() {

    $analyzerPHP = new Analyzer();
    $sentence = "I just got a call from David - does he realize it's Sunday?";
    // PHP sentiment Analyzer
    $resPHP = $analyzerPHP->getSentiment($sentence);
    // Detoxify
    $answer = $this->detoxify->analyzer();
    $resDetox = $answer['TOXICITY'];
    // Google perspective API
    $perAnswer = $this->perspective->analyzer();
    var_dump($perAnswer);

    $output = [
      '#type' => 'html',
      '#theme' => 'analyzer',
      '#title' => 'Analyzer',
      '#attached' => [
        'library' => [
          'sentiment/analyzer',
        ],
      ],
      '#variables' => [
        'detoxify' => $resDetox,
        'phpAnalyzer' => $resPHP
      ],
    ];
    return $output;
  }

}
