<?php

use Khaos\Bench2\Bench;
use Khaos\Bench2\Tool\Bench\BenchPackage;
use Khaos\Bench2\Tool\ToolPackageRepository;
use Khaos\Schema\InstanceFactoryCollection;
use Khaos\Schema\SchemaRepository;
use Khaos\Schema\SchemaInstanceRepository;
use Khaos\Schema\SchemaInstanceValidator;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once __DIR__.'/../../../../vendor/autoload.php';

$toolPackageRepository = new ToolPackageRepository();
$toolPackageRepository->add(new BenchPackage());

return new Bench($toolPackageRepository);