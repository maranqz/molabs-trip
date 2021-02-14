<?php


namespace TripBundle\Manipulator;


use TripBundle\Entity\Trip;

interface TripManipulatorInterface
{
    public function create(Trip $trip): Trip;

    public function update(Trip $trip): Trip;

    public function delete(Trip $trip): bool;

    public function byIdOrThrowException(int $tripId): Trip;
}