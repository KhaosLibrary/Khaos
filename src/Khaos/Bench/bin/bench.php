#!/usr/bin/php
<?php

$start = microtime(true);

require_once __DIR__.'/../src/bootstrap.php';

$end = microtime(true);

echo "\nTime: ".($end - $start)."\n";