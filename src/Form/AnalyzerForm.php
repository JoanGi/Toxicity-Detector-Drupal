<?php

namespace Drupal\sentiment\Form;

/**
 * @file
 * Contains \Drupal\sentiment\Form\sentimentForm.
 */

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Renderer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactory;
use Drupal\taxonomy\Entity\Term;
use Drupal\user\Entity\User;

/**
 * MealPlannerFront form Class.
 */
class AnalyzerForm extends FormBase {
  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  /**
   * Configuration state Drupal Site.
   *
   * @var array
   */
  protected $configFactory;
  /**
   * Render service.
   *
   * @var array
   */
  protected $renderer;

  /**
   * Construct fucntion.
   */
  public function __construct(ConfigFactory $configFactory, MealPlan $mealplanner, Renderer $renderer) {
    $this->configFactory = $configFactory;
    $this->renderer = $renderer;
  }

  /**
   * Create fucntion. Setting dependency injection.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('renderer')
    );
  }

  /**
   * Get form name.
   */
  protected function getEditableConfigNames() {
    return [
      'sentiment.front',
    ];
  }

  /**
   * Main form.
   *
   * @param array $form
   *   Form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   *
   * @return array
   *   Return array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    return $form;
  }

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'mealplanner_form';
  }

  /**
   * Validate function.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    /* Example data to check if the provided settings are okay */
  }

  /**
   * Submit function.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
