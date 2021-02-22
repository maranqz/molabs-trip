<?php

namespace App\Service\Countries;

use App\Entity\Country;
use Doctrine\ORM\EntityManagerInterface;

class Sync
{
    private $em;
    private $sdk;
    private $portion;

    public function __construct(EntityManagerInterface $em, SDK $sdk, int $portion = 50)
    {
        $this->em = $em;
        $this->sdk = $sdk;
        $this->portion = $portion;
    }

    public function countries()
    {
        if (!$this->isTableEmpty()) {
            throw new \Exception('Table countries should be empty');
        }

        $this->em->transactional(function () {
            foreach ($this->sdk->countries() as $index => $countryData) {
                $country = new Country($countryData['alpha3Code']);
                $country->setName($countryData['name']);
                $country->setRegion($countryData['region']);

                $this->em->persist($country);

                if (0 == $index % $this->portion) {
                    $this->em->flush();
                }
            }
            $this->em->flush();
        });
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
