<?php


namespace TripBundle\Tests\Helper;


use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use TripBundle\Validator\EntityExist;
use TripBundle\Validator\NotOverlapping;
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

    public static function Greater($value)
    {
        return static::initClass(GreaterThan::class, ['value' => $value]);
    }

    public static function GreaterDateMessage($value, $format = 'M d, Y, g:i A')
    {
        return str_replace(
            '{{ compared_value }}',
            $value->format($format),
            Validator::Greater($value)->message
        );
    }

    public static function EntityExist()
    {
        return static::initClass(EntityExist::class);
    }

    public static function EntityExistMessage($entity)
    {
        return str_replace(
            '%entity%',
            $entity,
            Validator::EntityExist()->message
        );
    }

    public static function UniqueEntity($fields = ['email'])
    {
        return static::initClass(UniqueEntity::class, ['fields' => $fields]);
    }

    public static function NotOverlapping()
    {
        return static::initClass(NotOverlapping::class);
    }

    protected static function initClass($class, $options = [])
    {
        return new $class($options);
    }
}