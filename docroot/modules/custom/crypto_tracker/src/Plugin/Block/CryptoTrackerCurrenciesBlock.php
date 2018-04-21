<?php

namespace Drupal\crypto_tracker\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\crypto_tracker\CryptoTrackerClient;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides a 'Top cryptocurrencies' block.
 *
 * Drupal\Core\Block\BlockBase gives us a very useful set of basic functionality
 * for this configurable block. We can just fill in a few of the blanks with
 * defaultConfiguration(), blockForm(), blockSubmit(), and build().
 *
 * @Block(
 *   id = "crypto_tracker_currencies",
 *   admin_label = @Translation("Crypto Tracker Currencies")
 * )
 */
class CryptoTrackerCurrenciesBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $client;

  /**
   * {@inheritdoc}
   */
  public function __construct(CryptoTrackerClient $client, $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->client = $client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('crypto_tracker.client'),
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * {@inheritdoc}
   *
   * This method sets the block default configuration. This configuration
   * determines the block's behavior when a block is initially placed in a
   * region. Default values for the block configuration form should be added to
   * the configuration array. System default configurations are assembled in
   * BlockBase::__construct() e.g. cache setting and block title visibility.
   *
   * @see \Drupal\block\BlockBase::__construct()
   */
  public function defaultConfiguration() {
    return [
      'crypto_tracker_currencies_block_currency_count' => '10',
    ];
  }

  /**
   * {@inheritdoc}
   *
   * This method defines form elements for custom block configuration. Standard
   * block configuration fields are added by BlockBase::buildConfigurationForm()
   * (block title and title visibility) and BlockFormController::form() (block
   * visibility settings).
   *
   * @see \Drupal\block\BlockBase::buildConfigurationForm()
   * @see \Drupal\block\BlockFormController::form()
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['crypto_tracker_currencies_block_currency_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Cryptocurrencies count'),
      '#description' => $this->t('Define the number of cryptocurrencies to display.'),
      '#default_value' => $this->configuration['crypto_tracker_currencies_block_currency_count'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   *
   * This method processes the blockForm() form fields when the block
   * configuration form is submitted.
   *
   * The blockValidate() method can be used to validate the form submission.
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['crypto_tracker_currencies_block_currency_count']
      = $form_state->getValue('crypto_tracker_currencies_block_currency_count');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $limit = $this->configuration['crypto_tracker_currencies_block_currency_count'];
    $response = $this->client->getCurrency('', $limit);
    $render_array['currency_list'] = [
      // The theme function to apply to the #items.
      '#theme' => 'crypto_tracker',
      '#list_type' => 'ol',
      // The list itself.
      '#items' => $response,
    ];
    return $render_array;
  }

}
