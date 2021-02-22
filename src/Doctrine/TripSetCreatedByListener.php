<?php

namespace App\Doctrine;

use App\Entity\Trip;
use Symfony\Component\Security\Core\Security;

class TripSetCreatedByListener
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Trip $trip)
    {
        if ($trip->getCreatedBy()) {
            return;
        }

        if ($this->security->getUser()) {
            $trip->setCreatedBy($this->security->getUser());
        }
    }
}
