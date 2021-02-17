<?php


namespace TripBundle\Api;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use TripBundle\Entity\Country;

class CountriesApi implements CountriesApiInterface
{
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->repository = $manager->getRepository(Country::class);
    }

    /**
     * @inheritDoc
     */
    public function getCountries(&$responseCode, array &$responseHeaders)
    {
        return $this->repository->findAll();
    }
}