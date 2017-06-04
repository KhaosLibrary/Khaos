<?php

use Khaos\Bench\Bench;

require_once __DIR__.'/../vendor/autoload.php';

$injector   = require_once __DIR__.'/_bootstrap/di.php';
$dispatcher = require_once __DIR__.'/_bootstrap/events.php';

/** @var Bench $bench */
$bench = $injector->make(Bench::class);
$bench->import(Bench::getRootResourceDefinition(getcwd()));
$bench->run();