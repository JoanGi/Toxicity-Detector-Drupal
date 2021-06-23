<?php

namespace Drupal\sentiment\Controller;

/**
 * @file
 * Contains \Drupal\sentiment\Controller\MealPlan.
 */


use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Analyzer main class.
 */
class DetoxifyConnector extends ControllerBase {
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
   * Construct method.
   */
  public function __construct(
    ConfigFactory $configFactory,
    Json $serialization) {
    $this->configFactory = $configFactory;
    $this->serialization = $serialization;
  }

  /**
   * Create method.
   */
  public static function create(ContainerInterface $container) {
    // SET DEPENDENCY INJECTION.
    return new static(
      $container->get('config.factory'),
      $container->get('serialization.json'),
    );
  }

  /**
   * Main method. Get mealplan render array.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   *
   * @return array
   *   Mealplan render array.
   */
  public function analyzer($string) {

    $baseUrl = 'localhost:8000';
    $client = new Client([
      'headers' => [
        'content-type' => 'application/json',
      ],
    ]);

    try {
      $url = $baseUrl . '/analyzeRequest';
      $response = $client->request('POST', $url, [
        'body' => JSON::encode(
          [
            'input' => $string,
          ])
      ]);
      $answer = JSON::decode($response->getBody()->getContents());
      return $answer;
    }
    catch (RequestException $e) {
      return FALSE;
    }
  }

}
