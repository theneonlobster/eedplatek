<?php

namespace Drupal\crypto_tracker;

use Drupal\Core\Config\ConfigFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Cache\CacheBackendInterface;

/**
 * {@inheritdoc}
 */
class CryptoTrackerClient {

  use StringTranslationTrait;

  protected $client;

  protected $config;

  protected $logger;

  protected $cacheBackend;

  /**
   * {@inheritdoc}
   */
  public function __construct(Client $client, ConfigFactory $config, $logger, CacheBackendInterface $cacheBackend) {
    $this->client = $client;
    $this->baseUrl = $config->get('crypto_tracker.settings')->get('endpoint');
    $this->cachePeriod = $config->get('crypto_tracker.settings')->get('cache_period');
    $this->loggerFactory = $logger;
    $this->cacheBackend = $cacheBackend;
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrency($currency = '', $limit = '') {
    if ($currency) {
      $currency = str_replace(' ', '-', strtolower($currency));
    }
    $options = [
      'query' => [
        'limit' => $limit,
      ],
    ];

    try {
      $cid = ($currency ? $currency : 'ticker' . ($limit ? '-' . $limit : ''));
      if ($cache = $this->cacheBackend->get($cid)) {
        $data = $cache->data;
      }
      else {
        $request = $this->client->get($this->baseUrl . $currency, $options);
        $expire = time() + $this->cachePeriod;
        $data = json_decode($request->getBody(), TRUE);
        $this->cacheBackend->set($cid, $data, $expire);
      }
      return $data;
    }
    catch (RequestException $e) {
      $this->loggerFactory->get('crypto_tracker')->warning('Failed due to "%error".', [
        '%error' => $e->getMessage(),
      ]);
      drupal_set_message($this->t('Failed due to "%error".', [
        '%error' => $e->getMessage(),
      ]));
      return;
    }
  }

}
