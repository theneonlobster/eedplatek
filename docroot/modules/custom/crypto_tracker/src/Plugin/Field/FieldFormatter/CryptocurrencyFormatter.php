<?php

namespace Drupal\crypto_tracker\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use GuzzleHttp\Client;

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
class CryptoCurrencyFormatter extends FormatterBase {

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
      // TODO: sanititze the cryptocurrency name (lowercase and replace spaces).
      $uri = 'api.coinmarketcap.com/v1/ticker/' . $item->value;
      $client = new Client();
      $request = $client->get($uri);

      $response = json_decode($request->getBody(), TRUE);
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
