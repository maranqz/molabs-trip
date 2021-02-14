<?php


namespace TripBundle\Manipulator;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use TripBundle\Entity\Trip;

class TripManipulator implements TripManipulatorInterface
{
    private EntityManagerInterface $em;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
        $this->repository = $manager->getRepository(Trip::class);
    }

    public function create(Trip $trip): Trip
    {
        $this->em->persist($trip);
        $this->em->flush();

        return $trip;
    }

    public function update(Trip $trip): Trip
    {
        $this->em->flush();

        return $trip;
    }

    public function delete(Trip $trip): bool
    {
        $this->em->remove($trip);
        $this->em->flush();

        return true;
    }

    public function byIdOrThrowException(int $tripId): Trip
    {
        /** @var Trip $trip */
        $trip = $this->repository->find($tripId);

        if (!$trip) {
            throw new \InvalidArgumentException(sprintf('Account identified by "%s" id does not exist.', $tripId));
        }

        return $trip;
    }
}