<?php

namespace Drupal\crypto_tracker\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\crypto_tracker\CryptoTrackerClient;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Plugin implementation of the 'cryptocurrency_widget' widget.
 *
 * @FieldWidget(
 *   id = "cryptocurrency_widget",
 *   module = "crypto_tracker",
 *   label = @Translation("Select from a list of cryptocurrencies"),
 *   field_types = {
 *     "cryptocurrency"
 *   }
 * )
 */
class CryptocurrencyWidget extends WidgetBase implements ContainerFactoryPluginInterface {

  protected $client;

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, CryptoTrackerClient $client) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->client = $client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('crypto_tracker.client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $response = $this->client->getCurrency();
    // Parse the response and add values to an array.
    $options = [];
    foreach ($response as $currency) {
      $options[$currency['name']] = $currency['name'];
    }
    $element += [
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
    ];
    return ['value' => $element];
  }

}
