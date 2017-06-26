<?php

namespace Khaos\Bench;

use Khaos\Bench\Resource\Definition\DefinitionRepository;
use Symfony\Component\EventDispatcher\Event;

class PrepareToolsEvent extends Event
{
    const NAME = 'bench.prepare_tools';

    /**
     * @var Bench
     */
    private $bench;

    /**
     * @var DefinitionRepository
     */
    private $definitionRepository;

    /**
     * PrepareBenchToolsEvent constructor.
     *
     * @param Bench $bench
     * @param DefinitionRepository $definitionRepository
     */
    public function __construct(Bench $bench, DefinitionRepository $definitionRepository)
    {
        $this->bench                = $bench;
        $this->definitionRepository = $definitionRepository;
    }

    /**
     * @return Bench
     */
    public function getBench(): Bench
    {
        return $this->bench;
    }

    /**
     * @return DefinitionRepository
     */
    public function getDefinitionRepository(): DefinitionRepository
    {
        return $this->definitionRepository;
    }


}