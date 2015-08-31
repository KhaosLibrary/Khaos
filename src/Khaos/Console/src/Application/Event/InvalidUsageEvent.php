<?php

namespace Khaos\Console\Application\Event;

use Khaos\Console\Usage\Model\OptionDefinitionRepository;
use Khaos\Console\Usage\Parser\InputSequence;
use Khaos\Console\Usage\Parser\InputSequenceFactory;
use Symfony\Component\EventDispatcher\Event;

class InvalidUsageEvent extends Event
{
    private $input;

    public function __construct($args, OptionDefinitionRepository $definitionRepository)
    {
        $this->input = (new InputSequenceFactory())->createFrom($args, $definitionRepository);
    }

    /**
     * @return InputSequence
     */
    public function getInputSequence()
    {
        return clone $this->input;
    }
}
