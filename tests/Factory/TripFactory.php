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
    protected function getDefaults(): array
    {
        $startedAt  = self::faker()->dateTimeBetween('-30 years', '-1 year');
        $finishedAt = clone $startedAt;
        $finishedAt->add(new \DateInterval('P1D'));

        return [
            'createdBy'  => AccountFactory::new(),
            'country'    => CountryFactory::new(),
            'startedAt'  => $startedAt,
            'finishedAt' => $finishedAt,
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this// ->afterInstantiate(function(Trip $trip) {})
            ;
    }

    protected static function getClass(): string
    {
        return Trip::class;
    }
}
