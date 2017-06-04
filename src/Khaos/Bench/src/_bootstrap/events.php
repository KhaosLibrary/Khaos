<?php

use Auryn\Injector;
use Khaos\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Resource\DefinitionRepository\DefinitionImporter;
use Khaos\Bench\Resource\DefinitionRepository\DefinitionRepositoryImportEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @var Injector $injector
 * @var EventDispatcher $dispatcher
 */

$dispatcher = $injector->make(EventDispatcher::class);
$dispatcher->addListener(DefinitionRepositoryImportEvent::EVENT.'::'.ImportDefinition::TYPE, $injector->make(DefinitionImporter::class));

return $dispatcher;