<?php

namespace Drupal\cryptalexa\EventSubscriber;

use Drupal\alexa\AlexaEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\crypto_tracker\CryptoTrackerClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * An event subscriber for Alexa request events.
 */
class RequestSubscriber implements EventSubscriberInterface {

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

    switch ($request->intentName) {
      case 'AMAZON.HelpIntent':
        $response->respond('You can ask anything and I will respond with "Hello Drupal"');
        break;

      case 'CryptocurrencyValue':
        // Find the value of the slot in the request from Amazon
        $currency = $request->data['request']['intent']['slots']['Cryptocurrency']['resolutions']['resolutionsPerAuthority'][0]['values'][0]['value']['name'];
        // Pass the value to the client.
        $cmcresponse = $this->client->getCurrency($currency);
        // Respond to the request with the price.
        $response->respond($cmcresponse[0]['price_usd']);
        break;

      default:
        $response->respond('Hello Drupal');
        break;
    }
  }

}
