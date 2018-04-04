<?php

namespace Drupal\crypto_tracker\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\crypto_tracker\CryptoTrackerClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller routines for crypto tracker routes.
 */
class CryptoTrackerController extends ControllerBase {

  protected $client;

  /**
   * {@inheritdoc}
   */
  public function __construct(CryptoTrackerClient $client) {
    $this->client = $client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('crypto_tracker.client')
    );
  }

  /**
   * Callback for the crypto_tracker route.
   */
  public function ticker() {
    $response = $this->client->getCurrency();

    $render_array['currency_list'] = [
      // The theme function to apply to the #items.
      '#theme' => 'crypto_tracker',
      '#list_type' => 'ol',
      // The list itself.
      '#items' => $response,
    ];
    return $render_array;
  }

  /**
   * Callback for the currency_tracker route.
   */
  public function currency($currency) {
    $response = $this->client->getCurrency($currency);

    $render_array['currency_detail'] = [
      // The theme function to apply to the #items.
      '#theme' => 'currency_tracker',
      '#list_type' => 'ul',
      // The list itself.
      '#items' => $response,
    ];
    return $render_array;
  }

}
