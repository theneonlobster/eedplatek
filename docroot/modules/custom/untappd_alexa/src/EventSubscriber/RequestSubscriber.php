<?php

namespace Drupal\untappd_alexa\EventSubscriber;

use Drupal\alexa\AlexaEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\untappd\UntappdClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * An event subscriber for Alexa request events.
 */
class RequestSubscriber implements EventSubscriberInterface {

  protected $client;

  /**
   * {@inheritdoc}
   */
  public function __construct(UntappdClient $client) {
    $this->client = $client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('untappd.client')
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

      case 'OnTap':
        $bar = $request->data['request']['intent']['slots']['Bar']['resolutions']['resolutionsPerAuthority'][0]['values'][0]['value']['name'];
        $untappd_response = $this->client->get('venue/info/' . $bar);
        $beers = [];
        foreach ($untappd_response['response']['venue']['media']['items'] as $beer) {
          $beers[$beer['beer']['bid']] = $beer['beer']['beer_name'];
        }
        $response->respond(implode(", ", $beers));
        break;

      default:
        $response->respond('Hello Drupal');
        break;
    }
  }

}
