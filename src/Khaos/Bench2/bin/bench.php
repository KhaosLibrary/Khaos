#!/usr/bin/php
<?php

use Khaos\Bench2\BenchApplication;

$start = microtime(true);

/** @var BenchApplication $bench */
$bench = include __DIR__.'/bootstrap.inc.php';
$bench->import('bench.yaml');
$bench->run($argv);

echo "\n".number_format((microtime(true) - $start), 3)."s\n";
