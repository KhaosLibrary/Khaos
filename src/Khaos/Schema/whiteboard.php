<?php

use Khaos\Cache\FileCacheItemPool;
use Khaos\Schema\InstanceFactoryCollection;
use Khaos\Schema\CommandInstanceFactory;
use Khaos\Schema\CommandSchema;
use Khaos\Schema\FileDataProvider;
use Khaos\Schema\KeywordCollection;
use Khaos\Schema\Keywords\DescriptionKeyword;
use Khaos\Schema\Keywords\PropertiesKeyword;
use Khaos\Schema\Keywords\SelfKeyword;
use Khaos\Schema\Keywords\TypeKeyword;
use Khaos\Schema\SchemaRepository;
use Khaos\Schema\SchemaInstanceRepository;
use Khaos\Schema\SchemaInstanceValidator;

require_once __DIR__.'/../../../vendor/autoload.php';

