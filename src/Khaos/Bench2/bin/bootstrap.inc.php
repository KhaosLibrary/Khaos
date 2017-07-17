<?php

use Khaos\Bench2\BenchApplication;
use Khaos\Bench2\Tool\Bench\BenchPackage;
use Khaos\Bench2\Tool\Console\ConsolePackage;
use Khaos\Bench2\Tool\ToolPackageRepository;
use Khaos\Bench2\Tool\Twig\TwigPackage;
use Khaos\Schema\InstanceFactoryCollection;
use Khaos\Schema\SchemaRepository;
use Khaos\Schema\SchemaInstanceRepository;
use Khaos\Schema\SchemaInstanceValidator;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once __DIR__.'/../../../../vendor/autoload.php';

$toolPackageRepository = new ToolPackageRepository();
$toolPackageRepository->add(new BenchPackage());
$toolPackageRepository->add(new ConsolePackage());
$toolPackageRepository->add(new TwigPackage());

return new BenchApplication($toolPackageRepository);