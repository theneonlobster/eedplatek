<?php

namespace Drupal\untappd;

use Drupal\Core\Config\ConfigFactory;
use GuzzleHttp\Client;

/**
 * {@inheritdoc}
 */
class UntappdClient {

  protected $client;

  protected $config;

  /**
   * {@inheritdoc}
   */
  public function __construct(Client $client, ConfigFactory $config) {
    $this->client = $client;
    $this->clientId = $config->get('untappd.settings')->get('clientid');
    $this->clientSecret = $config->get('untappd.settings')->get('clientsecret');
  }

  /**
   * {@inheritdoc}
   */
  public function get($resource = 'search/brewery', $query = '') {
    $endpoint = 'https://api.untappd.com/v4';
    $options = [
      'query' => [
        'client_id' => $this->clientId,
        'client_secret' => $this->clientSecret,
      ],
    ];
    if ($query) {
      $options['query']['q'] = $query;
    }
    $uri = $endpoint . '/' . $resource;
    $request = $this->client->get($uri, $options);
    return json_decode($request->getBody(), TRUE);
  }

}
