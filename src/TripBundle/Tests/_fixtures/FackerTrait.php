<?php


namespace _fixtures;


use Faker\Factory;

trait FackerTrait
{
    public function getFacker()
    {
        static $facker;

        if (empty($facker)) {
            $facker = Factory::create();
        }

        return $facker;
    }
}