<?php

namespace App\Tests\Factory;

use App\Entity\Country;
use App\Repository\CountryRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Country|Proxy createOne(array $attributes = [])
 * @method static Country[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Country|Proxy findOrCreate(array $attributes)
 * @method static Country|Proxy random(array $attributes = [])
 * @method static Country|Proxy randomOrCreate(array $attributes = [])
 * @method static Country[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Country[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CountryRepository|RepositoryProxy repository()
 * @method Country|Proxy create($attributes = [])
 */
final class CountryFactory extends ModelFactory
{

    protected function getDefaults(): array
    {
        return [
            'code'   => self::faker()->regexify('[A-Z]{3}'),
            'name'   => self::faker()->country,
            'region' => self::faker()->regexify('[A-Z]{10}'),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this// ->afterInstantiate(function(Country $country) {})
            ;
    }

    protected static function getClass(): string
    {
        return Country::class;
    }
}
