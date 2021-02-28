<?php

namespace TripBundle\Doctrine;

use Symfony\Component\Security\Core\Security;
use TripBundle\Entity\Trip;

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
