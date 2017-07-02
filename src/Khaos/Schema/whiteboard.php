<?php

use Khaos\Schema\Instance\Validator\RecursiveValidator;
use Khaos\Schema\Instance\Validator\TypeValidator\ObjectTypeValidator;
use Khaos\Schema\Instance\Validator\TypeValidator\StringTypeValidator;
use Khaos\Schema\Instance\Validator\TypeValidator\TypeValidatorCollection;
use Khaos\Schema\SchemaInstanceValidator;

require_once __DIR__.'/../../../vendor/autoload.php';



$instance = new stdClass();
$instance->id          = '10';
$instance->title       = 'Example Title';
$instance->description = 'Example Description';




$type = new TypeValidatorCollection();
$type->add(new ObjectTypeValidator());
$type->add(new StringTypeValidator());

$recursiveValidator = new RecursiveValidator($type);

$validator = new SchemaInstanceValidator($recursiveValidator);


$result = $validator->validate($schema, $instance);

