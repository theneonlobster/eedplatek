<?php

namespace Drupal\untappd\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactory;

/**
 * Implements a form for configuration.
 */
class UntappdSettingsForm extends ConfigFormBase {

  protected $baseUrl;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactory $config) {
    $this->clientId = $config->get('untappd.settings')->get('clientid');
    $this->clientSecret = $config->get('untappd.settings')->get('clientsecret');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'untappd_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['untappd.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['untappd_description'] = [
      '#markup' => $this->t('This module allows Drupal to communicate with the Untappd API.'),
    ];

    $form['clientid'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Untappd Client ID'),
      '#description' => $this->t("The Untappd App's Client ID"),
      '#required' => TRUE,
      '#default_value' => $this->clientId,
    ];

    $form['clientsecret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Untappd Client Secret'),
      '#description' => $this->t("The Untappd App's Client Secret"),
      '#required' => TRUE,
      '#default_value' => $this->clientSecret,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $list = [];
    $this->buildAttributeList($list, $form_state->getValues());
    $config = $this->config('untappd.settings');

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
