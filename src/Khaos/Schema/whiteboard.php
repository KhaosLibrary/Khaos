<?php

use Khaos\Schema\KeywordCollection;
use Khaos\Schema\Keywords\TypeKeyword;
use Khaos\Schema\SchemaInstanceValidator;

require_once __DIR__.'/../../../vendor/autoload.php';

$schema = [
    'type' => 'string'
];

$instance = "123";


$keywords = new KeywordCollection();
$keywords->add(new TypeKeyword());

$validator = new SchemaInstanceValidator($keywords);

var_dump($validator->validate($schema, $instance));