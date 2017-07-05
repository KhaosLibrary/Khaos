<?php

use Khaos\Schema\KeywordCollection;
use Khaos\Schema\Keywords\PropertiesKeyword;
use Khaos\Schema\Keywords\TypeKeyword;
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
$validator->validate($schema, $instance);

var_dump($instance);

