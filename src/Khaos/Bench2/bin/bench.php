#!/usr/bin/php
<?php

use Khaos\Bench2\Bench;

$start = microtime(true);

/** @var Bench $bench */
$bench = include __DIR__.'/bootstrap.inc.php';
//$bench->enable('docker');
//$bench->enable('twig');
$bench->import('bench.yaml');
$bench->run($argv);

echo "\n".number_format((microtime(true) - $start), 3)."s\n";
