<?php

namespace Khaos\Bench;

use Khaos\Bench\Resource\Definition\DefinitionRepository;
use Symfony\Component\EventDispatcher\Event;

class CacheDefinitionsEvent extends Event
{
    const NAME = 'bench.cache_command_definitions';

    /**
     * @var Bench
     */
    private $bench;

    /**
     * @var DefinitionRepository
     */
    private $definitionRepository;

    /**
     * PrepareCommandDefinitionsEvent constructor.
     *
     * @param Bench                 $bench
     * @param DefinitionRepository  $definitionRepository
     */
    public function __construct(Bench $bench, DefinitionRepository $definitionRepository)
    {
        $this->bench                = $bench;
        $this->definitionRepository = $definitionRepository;
    }

    /**
     * Get Bench
     *
     * @return Bench
     */
    public function getBench()
    {
        return $this->bench;
    }

    /**
     * Get Definition Repository
     *
     * @return DefinitionRepository
     */
    public function getDefinitionRepository()
    {
        return $this->definitionRepository;
    }
}