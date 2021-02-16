<?php


namespace TripBundle\Api;


use Symfony\Component\Security\Core\Security;
use TripBundle\Entity\Trip as Entity;
use TripBundle\Manipulator\TripManipulatorInterface;
use TripBundle\Model\Trip;
use TripBundle\Model\TripCreate;
use TripBundle\Model\TripUpdate;
use TripBundle\Repository\TripRepository;

class TripsApi implements TripsApiInterface
{
    private TripManipulatorInterface $manipulator;
    private TripRepository $repository;
    private Security $security;

    public function __construct(TripManipulatorInterface $manipulator, TripRepository $repository, Security $security)
    {
        $this->manipulator = $manipulator;
        $this->repository = $repository;
        $this->security = $security;
    }

    public function setBasicAuth($value)
    {
        // TODO: Implement setBearerAuth() method.
    }

    /**
     * @inheritDoc
     */
    public function createTrip(TripCreate $dto, &$responseCode, array &$responseHeaders)
    {
        $trip = new Entity();
        $trip->setStartedAt($dto->getStartedAt())
            ->setFinishedAt($dto->getFinishedAt())
            ->setNotes($dto->getNotes())
            ->setCountry($dto->getCountry())
            ->setCreatedBy($dto->getCreatedBy());

        $this->manipulator->create($trip);

        return Trip::fromEntity($trip);
    }

    /**
     * @inheritDoc
     */
    public function deleteTrip($tripId, &$responseCode, array &$responseHeaders)
    {
        $account = $this->manipulator->byIdOrThrowException($tripId);

        return $this->manipulator->delete($account);
    }

    /**
     * @inheritDoc
     */
    public function getTrip($tripId, &$responseCode, array &$responseHeaders)
    {
        $trip = $this->manipulator->byIdOrThrowException($tripId);

        return Trip::fromEntity($trip);
    }


    public function getTrips($filter = null, &$responseCode, array &$responseHeaders)
    {
        return $this->repository->findByFilter(
            $filter->getCountry(),
            $filter->getStartedAt(),
            $filter->getFinishedAt(),
            $this->security->getUser()
        );
    }


    /**
     * @inheritDoc
     */
    public function updateTrip($tripId, TripUpdate $dto, &$responseCode, array &$responseHeaders)
    {
        $updatableTrip = $this->manipulator->byIdOrThrowException($tripId);

        if (!empty($dto->getCountry())) {
            $updatableTrip->setCountry($dto->getCountry());
        }
        if (!empty($dto->getStartedAt())) {
            $updatableTrip->setStartedAt($dto->getStartedAt());
        }
        if (!empty($dto->getFinishedAt())) {
            $updatableTrip->setFinishedAt($dto->getFinishedAt());
        }
        if (!empty($dto->getNotes())) {
            $updatableTrip->setNotes($dto->getNotes());
        }

        $this->manipulator->update($updatableTrip);

        return Trip::fromEntity($updatableTrip);
    }
}