<?php

namespace TripBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;

class TripBundle extends Bundle
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_TRIP = 'ROLE_TRIP';
    const IS_TRIP_ROLE = "is_granted('".self::ROLE_TRIP."')";
    const IS_ANONYMOUSLY = "is_granted('".AuthenticatedVoter::IS_AUTHENTICATED_ANONYMOUSLY."')";
}
