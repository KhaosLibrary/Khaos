#!/usr/bin/php
<?php

use Khaos\Bench\Bench;

// benchmark :: START
$start = microtime(true);


require_once __DIR__.'/../../../../vendor/autoload.php';
$injector = require_once __DIR__.'/bench/config/di.php';

/** @var Bench $bench */
$bench = $injector->make(Bench::class);
$bench->import(Bench::getRootResourceDefinition(getcwd()));
$bench->run(array_merge(['bench'], array_slice($argv, 1)));


// Benchmark :: END
echo "\nTime: ".(microtime(true) - $start)."\n";
