<?php

namespace Drupal\crypto_tracker\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\crypto_tracker\CryptoTrackerClient;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Plugin implementation of the 'Cryptocurrency' formatter.
 *
 * @FieldFormatter(
 *   id = "cryptocurrency_formatter",
 *   label = @Translation("Cryptocurrency"),
 *   field_types = {
 *     "cryptocurrency"
 *   }
 * )
 */
class CryptoCurrencyFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  protected $client;

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, CryptoTrackerClient $client) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
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
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('crypto_tracker.client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Displays cryptocurrency details.');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    foreach ($items as $delta => $item) {
      $response = $this->client->getCurrency($item->value);
      // Render each element as markup.
      $element[$delta] = [
        // The theme function to apply to the #items.
        '#theme' => 'currency_tracker',
        '#list_type' => 'ul',
        // The list itself.
        '#items' => $response,
      ];
    }
    return $element;
  }

}
