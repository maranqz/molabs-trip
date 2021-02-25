<?php

namespace App\Service\Countries;

use App\Entity\Country;
use Doctrine\ORM\EntityManagerInterface;

class Sync
{
    const PORTION_DEFAULT = 50;

    private $em;
    private $sdk;
    private $portion;
    private $regions;

    public function __construct(
        EntityManagerInterface $em,
        SDK $sdk,
        int $portion = self::PORTION_DEFAULT,
        $regions = []
    ) {
        $this->em = $em;
        $this->sdk = $sdk;
        $this->portion = $portion;
        $this->regions = $regions;
    }

    public function countries($portion = null, $regions = null)
    {
        if (!$this->isTableEmpty()) {
            throw new \Exception('Table countries should be empty');
        }

        $portion = $portion ?? $this->portion;
        $regions = $regions ?? $this->regions;

        $this->em->transactional(function () use ($regions, $portion) {
            foreach ($this->sdk->countries() as $index => $countryData) {
                if (!empty($regions) && !in_array($countryData['region'], $regions)) {
                    continue;
                }

                $country = new Country($countryData['alpha3Code']);
                $country->setName($countryData['name']);
                $country->setRegion($countryData['region']);

                $this->em->persist($country);

                if (0 == $index % $portion) {
                    $this->em->flush();
                    $this->em->clear();
                }
            }
        });
        $this->em->clear();
    }

    private function isTableEmpty()
    {
        $qb = $this->em->getRepository(Country::class)->createQueryBuilder('c');
        $result = $qb
            ->select(['1'])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return !boolval($result);
    }
}
