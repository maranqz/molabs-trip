<?php

use Codeception\Util\Autoload;

define("FIXTURES_DIR", __DIR__ . '/_data/');
Autoload::addNamespace('_fixtures', __DIR__ . '/_fixtures');