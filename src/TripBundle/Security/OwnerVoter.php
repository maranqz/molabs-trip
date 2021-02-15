<?php


namespace TripBundle\Security;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use TripBundle\Entity\Account;
use TripBundle\Entity\Trip;

class OwnerVoter extends Voter
{
    const ANY = 'any';
    const DEFAULT_ENTITIES_CLASS = [
        [
            self::CLASS_OPT => Account::class,
            self::GETTER => null,
        ],
        [
            self::CLASS_OPT => Trip::class,
            self::GETTER => 'getCreatedBy',
        ]
    ];
    const CLASS_OPT = 'class';
    const GETTER = 'getter';

    private $entitiesClass;

    public function __construct($entitiesClass = null)
    {
        $this->entitiesClass = $this->prepareEntitiesClass($entitiesClass);
    }

    private function prepareEntitiesClass($entitiesClass)
    {
        if (is_null($entitiesClass)) {
            $entitiesClass = self::DEFAULT_ENTITIES_CLASS;
        }
        $preparedClasses = [];

        foreach ($entitiesClass as $class) {
            if (array_key_exists(self::GETTER, $class) === false) {
                $class[self::GETTER] = null;
            }

            $preparedClasses[$class[self::CLASS_OPT]] = $class[self::GETTER];
        }

        return $preparedClasses;
    }

    protected function supports($attribute, $subject)
    {
        return $this->subjectIsSupported($subject);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof Account || !$this->subjectIsSupported($subject)) {
            return false;
        }

        return $this->getOwner($subject) === $user;
    }

    private function subjectIsSupported($subject)
    {
        return array_key_exists(get_class($subject), $this->entitiesClass);
    }

    private function getOwner($subject)
    {
        $getter = $this->entitiesClass[get_class($subject)];
        if (empty($getter)) {
            return $subject;
        }

        return $subject->$getter();
    }

}