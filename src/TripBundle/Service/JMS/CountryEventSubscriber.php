<?php


namespace TripBundle\Service\JMS;


use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use TripBundle\Model\Trip;
use TripBundle\Model\TripCreate;
use TripBundle\Model\TripUpdate;

class CountryEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        $subscribe = [
            'event' => 'serializer.pre_deserialize',
            'method' => 'onPreDeserialize',
        ];
        $subscribers = [];

        foreach ([TripCreate::class, TripUpdate::class, Trip::class] as $class) {
            $subscribers[] = $subscribe + ['class' => $class];
        }

        return $subscribers;
    }

    public function onPreDeserialize(PreDeserializeEvent $event)
    {
        $data = $event->getData();

        if (isset($data['country']) && !is_array($data['country'])) {
            $data['country'] = ['code' => $data['country']];
            $event->setData($data);
        }
    }
}