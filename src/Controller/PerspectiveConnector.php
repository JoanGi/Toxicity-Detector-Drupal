<?php

namespace Drupal\sentiment\Controller;

/**
 * @file
 * Contains \Drupal\sentiment\Controller\PerspectiveConnector.
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
class PerspectiveConnector extends ControllerBase {
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

    // Google perspective API
    $baseUrl = 'https://commentanalyzer.googleapis.com/v1alpha1/comments:analyze?key=AIzaSyDaUOPlrAcCgSP0ZkSpdFZ408pVcoKPP1E';
    $client = new Client([
      'headers' => [
        'Content-Type' => 'application/json',
      ],
    ]);

    try {
      $url = $baseUrl;
      $response = $client->request('POST', $url, [
        'body' =>
          '{
            "comment": {
              "text": "' . $string . '",
            },
            "requestedAttributes": {
              "TOXICITY":{}
            },
            "languages": ["en"],
          }'
      ]);
      $perspectiveAnswer = JSON::decode($response->getBody()->getContents());
      return $perspectiveAnswer;
    }
    catch (RequestException $e) {
      return $e->getResponse()->getBody()->getContents();
    }
  }

}
