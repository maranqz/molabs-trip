<?php

namespace TripBundle\Tests\Helper;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use TripBundle\Validator\NotOverlapping;

class Validator
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
        return static::initClass(UniqueEntity::class);
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

    public static function NotOverlapping()
    {
        return static::initClass(NotOverlapping::class);
    }

    protected static function initClass($class, $options = [])
    {
        return new $class($options);
    }
}
