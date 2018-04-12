<?php

namespace Drupal\crypto_tracker\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use GuzzleHttp\Client;

/**
 * Plugin implementation of the 'text_hero' widget.
 *
 * @todo make thies work with text fields instead of a custom field type.
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
class CryptocurrencyWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $payload = $this->thepayload();
    $element += [
      '#type' => 'select',
      '#options' => $payload,
    ];
    return ['value' => $element];
  }

  /**
   * TODO: Change the name of this function.
   */
  public function thepayload($param = NULL) {
    $uri = 'api.coinmarketcap.com/v1/ticker/';

    $client = new Client();
    $request = $client->get($uri);

    $response = json_decode($request->getBody(), TRUE);

    $currencies = $response;
    // Parse the reponse and add values to an array.
    $options = [];
    foreach ($currencies as $currency) {
      $options[$currency['name']] = $currency['name'];
    }
    return $options;
  }

}
