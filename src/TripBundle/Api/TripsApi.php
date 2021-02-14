<?php


namespace TripBundle\Api;


use TripBundle\Model\Trip;

class TripsApi implements TripsApiInterface
{
    /**
     * @inheritDoc
     */
    public function createTrip(Trip $trip, &$responseCode, array &$responseHeaders)
    {
        // TODO: Implement createTrip() method.
    }

    public function setBearerAuth($value)
    {
        // TODO: Implement setBearerAuth() method.
    }

    /**
     * @inheritDoc
     */
    public function deleteTrip($tripId, &$responseCode, array &$responseHeaders)
    {
        // TODO: Implement deleteTrip() method.
    }

    /**
     * @inheritDoc
     */
    public function getTrip($tripId, &$responseCode, array &$responseHeaders)
    {
        // TODO: Implement getTrip() method.
    }

    /**
     * @inheritDoc
     */
    public function updateTrip($tripId, Trip $trip, &$responseCode, array &$responseHeaders)
    {
        // TODO: Implement updateTrip() method.
    }
}