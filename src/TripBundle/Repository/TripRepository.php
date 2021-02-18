<?php


namespace TripBundle\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use TripBundle\Entity\Account;
use TripBundle\Entity\Country;
use TripBundle\Entity\Trip;
use TripBundle\Model\Trip as Model;

class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function findByFilter(?Country $country, ?\DateTime $startedAt, ?\DateTime $finishedAt, Account $account)
    {
        $criteria = Criteria::create();
        $criteria->andWhere($criteria->expr()->eq('createdBy', $account))
            ->orderBy(['startedAt' => 'ASC']);

        if (isset($country)) {
            $criteria->andWhere($criteria->expr()->eq('country', $country));
        }

        if (isset($startedAt)) {
            $criteria->andWhere($criteria->expr()->gte('startedAt', $startedAt));
        }

        if (isset($finishedAt)) {
            $criteria->andWhere($criteria->expr()->lte('finishedAt', $finishedAt));
        }

        return array_map([Model::class, 'fromEntity'], $this->matching($criteria)->getValues());
    }
}