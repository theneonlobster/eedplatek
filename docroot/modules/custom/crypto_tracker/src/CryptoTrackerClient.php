<?php

namespace Drupal\crypto_tracker;

use Drupal\Core\Config\ConfigFactory;
use GuzzleHttp\Client;

/**
 * {@inheritdoc}
 */
class CryptoTrackerClient {

  protected $client;

  protected $config;

  /**
   * {@inheritdoc}
   */
  public function __construct(Client $client, ConfigFactory $config) {
    $this->client = $client;
    $this->baseUrl = $config->get('crypto_tracker.settings')->get('endpoint');
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrency($currency = '', $limit = '') {
    $options = [
      'query' => [
        'limit' => $limit,
      ],
    ];

    $request = $this->client->get($this->baseUrl . $currency, $options);
    // @TODO: Add caching.
    // @TODO: Add error handling.
    // @TODO: Sanititze the cryptocurrency name (lowercase and replace spaces).
    return json_decode($request->getBody(), TRUE);
  }

}
