<?php

use Khaos\Cache\FileCacheItemPool;
use Khaos\Schema\CommandSchema;
use Khaos\Schema\FileDataProvider;
use Khaos\Schema\KeywordCollection;
use Khaos\Schema\Keywords\DescriptionKeyword;
use Khaos\Schema\Keywords\PropertiesKeyword;
use Khaos\Schema\Keywords\SelfKeyword;
use Khaos\Schema\Keywords\TypeKeyword;
use Khaos\Schema\SchemaCollection;
use Khaos\Schema\SchemaInstanceRepository;
use Khaos\Schema\SchemaInstanceValidator;

require_once __DIR__.'/../../../vendor/autoload.php';

$keywords = new KeywordCollection();
$keywords->add(new TypeKeyword());
$keywords->add(new PropertiesKeyword());
$keywords->add(new SelfKeyword());
$keywords->add(new DescriptionKeyword());

$validator = new SchemaInstanceValidator($keywords);

$schemas = new SchemaCollection();
$schemas->add(new CommandSchema());

$cachePool = new FileCacheItemPool(__DIR__.'/.cache');

$instances = new SchemaInstanceRepository($validator, $schemas, $cachePool);
$instances->add(new FileDataProvider('bench.yaml'));
