<?php

namespace App\Tests\Factory;

use App\Entity\Trip;
use App\Repository\TripRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Trip|Proxy createOne(array $attributes = [])
 * @method static Trip[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Trip|Proxy findOrCreate(array $attributes)
 * @method static Trip|Proxy random(array $attributes = [])
 * @method static Trip|Proxy randomOrCreate(array $attributes = [])
 * @method static Trip[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Trip[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TripRepository|RepositoryProxy repository()
 * @method Trip|Proxy create($attributes = [])
 */
final class TripFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://github.com/zenstruck/foundry#model-factories)
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Trip $trip) {})
        ;
    }

    protected static function getClass(): string
    {
        return Trip::class;
    }
}
