#!/usr/bin/php
<?php

use Khaos\Bench\Bench;
use Khaos\Bench\Tool\Docker\DockerTool;

$start = microtime(true);

/** @var Bench $bench */
$bench = include __DIR__.'/bootstrap.inc.php';
$bench->import('bench.yml');
$bench->run($argv);

echo "\n".number_format((microtime(true) - $start), 3)."s\n";
