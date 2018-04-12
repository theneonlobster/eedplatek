<?php

namespace Drupal\crypto_tracker\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'cryptocurrency' field type.
 *
 * @FieldType(
 *   id = "cryptocurrency",
 *   label = @Translation("Currency"),
 *   module = "crypto_tracker",
 *   description = @Translation("Demonstrates a field composed of a currency."),
 *   default_widget = "cryptocurrency_widget",
 *   default_formatter = "cryptocurrency_formatter"
 * )
 */
class CryptocurrencyItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'varchar',
          'length' => '255',
          'not null' => FALSE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Cryptocurrency'));

    return $properties;
  }

}
