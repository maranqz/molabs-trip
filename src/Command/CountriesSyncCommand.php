<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\Countries\Sync;

class CountriesSyncCommand extends Command
{
    protected static $defaultName = 'trip:countries:sync';
    protected static $defaultDescription = 'Importing countries from https://restcountries.eu/';

    private $sync;

    public function __construct(string $name = null, Sync $sync)
    {
        parent::__construct($name);

        $this->sync = $sync;
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->sync->countries();

        return 0;
    }
}
