<?php


namespace Helper;


use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use TripBundle\Validator\UniqueEntity;

class Validator extends \Codeception\Module
{
    public static function NotBlank()
    {
        return static::initClass(NotBlank::class);
    }

    public static function NotNull()
    {
        return static::initClass(NotNull::class);
    }

    public static function Email()
    {
        return static::initClass(Email::class);
    }

    public static function UniqueEntity()
    {
        return static::initClass(UniqueEntity::class, ['fields' => 'email']);
    }

    protected static function initClass($class, $options = [])
    {
        return new $class($options);
    }
}