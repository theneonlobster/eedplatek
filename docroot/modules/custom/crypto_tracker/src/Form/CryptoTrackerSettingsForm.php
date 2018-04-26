<?php

namespace Drupal\crypto_tracker\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactory;

/**
 * Implements a form for configuration.
 */
class CryptoTrackerSettingsForm extends ConfigFormBase {

  protected $baseUrl;

  protected $cachePeriod;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactory $config) {
    $this->baseUrl = $config->get('crypto_tracker.settings')->get('endpoint');
    $this->cachePeriod = $config->get('crypto_tracker.settings')->get('cache_period');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'crypto_tracker_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['crypto_tracker.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['crypto_tracker_description'] = [
      '#markup' => $this->t('This module displays live cryptocurrency values in blocks and pages.'),
    ];

    $form['endpoint'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API endpoint'),
      '#description' => $this->t('The URI of the service (excluding the protocol identifier).'),
      '#required' => TRUE,
      '#default_value' => $this->baseUrl,
    ];
    $form['cache_period'] = [
      '#type' => 'number',
      '#title' => $this->t('Cache period in seconds'),
      '#description' => $this->t('The period of time that will elapse before repeating a request.'),
      '#required' => TRUE,
      '#default_value' => $this->cachePeriod,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Trim the submitted value of whitespace and slashes.
    $baseUrl = ltrim(trim($form['endpoint']['#value']), " \\/");
    if (!empty($baseUrl)) {
      $form_state->setValueForElement($form['endpoint'], $baseUrl);
    }

    $strlen = strlen($baseUrl) - 1;

    if ($baseUrl && $baseUrl[$strlen] !== '/') {
      $form_state->setError($form['endpoint'], $this->t('The API endpoint needs to end with a slash.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $list = [];
    $this->buildAttributeList($list, $form_state->getValues());
    $config = $this->config('crypto_tracker.settings');

    foreach ($list as $key => $value) {
      $config->set($key, $value);
    }

    $config->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * Build the configuration form value list.
   */
  protected function buildAttributeList(
    array &$list = [],
    array $rawAttributes = [],
    $currentName = '') {
    foreach ($rawAttributes as $key => $rawAttribute) {
      $name = $currentName ? $currentName . '.' . $key : $key;
      if (in_array($name, [
        'op',
        'form_id',
        'form_token',
        'form_build_id',
        'submit',
      ])) {
        continue;
      }
      if (is_array($rawAttribute)) {
        $this->buildAttributeList($list, $rawAttribute, $name);
      }
      else {
        $list[$name] = $rawAttribute;
      }
    }
  }

}
