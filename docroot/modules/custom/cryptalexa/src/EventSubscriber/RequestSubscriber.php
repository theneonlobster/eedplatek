<?php

namespace Drupal\cryptalexa\EventSubscriber;

use Drupal\alexa\AlexaEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\crypto_tracker\CryptoTrackerClient;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactory;

/**
 * An event subscriber for Alexa request events.
 */
class RequestSubscriber implements EventSubscriberInterface {

  protected $client;

  protected $applicationID;

  /**
   * {@inheritdoc}
   */
  public function __construct(CryptoTrackerClient $client, ConfigFactory $config) {
    $this->client = $client;
    $this->applicationID = $config->get('cryptalexa.settings')->get('application_id');
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
   * Gets the event.
   */
  public static function getSubscribedEvents() {
    $events['alexaevent.request'][] = ['onRequest', 0];
    return $events;
  }

  /**
   * Called upon a request event.
   *
   * @param \Drupal\alexa\AlexaEvent $event
   *   The event object.
   */
  public function onRequest(AlexaEvent $event) {
    $request = $event->getRequest();
    $response = $event->getResponse();

    if ($this->request->applicationID == $this->applicationID) {
      switch ($request->intentName) {
        case 'AMAZON.HelpIntent':
          $response->respond('You can ask anything and I will respond with "Hello Drupal"');
          break;

        case 'CryptocurrencyValue':
          $currency = $request->data['request']['intent']['slots']['Cryptocurrency']['resolutions']['resolutionsPerAuthority'][0]['values'][0]['value']['name'];
          $cmcresponse = $this->client->getCurrency($currency);
          $response->respond($cmcresponse[0]['price_usd']);
          break;

        default:
          $response->respond('Hello Drupal');
          break;
      }
    }
  }

}
