<?php

namespace Khaos\Bench\Command\Event;

use Khaos\Console\Usage\Model\OptionDefinitionRepository;
use Khaos\Console\Usage\Parser\InputSequence;
use Khaos\Console\Usage\Parser\InputSequenceFactory;
use Symfony\Component\EventDispatcher\Event;

class InvalidUsageEvent extends Event
{
    const NAME = 'command.runner.invalidUsage';

    /**
     * @var InputSequence
     */
    private $input;

    private $args;

    /**
     * CommandRunnerInvalidUsageEvent constructor.
     *
     * @param array                       $args
     * @param OptionDefinitionRepository  $definitionRepository
     */
    public function __construct($args, OptionDefinitionRepository $definitionRepository)
    {
        $this->args  = $args;
        $this->input = (new InputSequenceFactory())->createFrom($args, $definitionRepository);
    }

    /**
     * @return InputSequence
     */
    public function getInputSequence()
    {
        return clone $this->input;
    }

    public function getArgs()
    {
        return $this->args;
    }
}
