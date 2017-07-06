<?php

use Khaos\Cache\FileCacheItemPool;
use Khaos\Schema\CommandSchema;
use Khaos\Schema\FileDataProvider;
use Khaos\Schema\KeywordCollection;
use Khaos\Schema\Keywords\PropertiesKeyword;
use Khaos\Schema\Keywords\TypeKeyword;
use Khaos\Schema\SchemaCollection;
use Khaos\Schema\SchemaInstanceRepository;
use Khaos\Schema\SchemaInstanceValidator;

require_once __DIR__.'/../../../vendor/autoload.php';

$schema = [
    'type' => 'object',
    'properties' => [
        'command' => ['type' => 'string'],
        'usage'   => ['type' => 'string'],
        'action'  => ['type' => 'string']
    ]
];

$instance = [
    'command' => 'help',
    'usage'   => 'bench help',
    'action'  => 'bench.help()'
];

$keywords = new KeywordCollection();
$keywords->add(new TypeKeyword());
$keywords->add(new PropertiesKeyword());

$validator = new SchemaInstanceValidator($keywords);

$schemas = new SchemaCollection();
$schemas->add(new CommandSchema());

$cachePool = new FileCacheItemPool(__DIR__.'/.cache/instances/');

$instances = new SchemaInstanceRepository($validator, $schemas, $cachePool);
$instances->add(new FileDataProvider('bench.yml'));
