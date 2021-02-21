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
            // ->afterInstantiate(function(Country $country) {})
        ;
    }

    protected static function getClass(): string
    {
        return Country::class;
    }
}
