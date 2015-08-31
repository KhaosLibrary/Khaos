<?php

namespace Khaos\FSM;

use Traversable;

class SequenceRunner implements Runner
{
    /**
     * @var StepRunner
     */
    private $stepRunner;

    /**
     * Sequence Runner
     *
     * Treats input as a sequence of symbols, in turn each is passed to the machine
     * taking the first accepting path at each state.
     *
     * @param StepRunner $stepRunner
     */
    public function __construct(StepRunner $stepRunner)
    {
        $this->stepRunner = $stepRunner;
    }

    /**
     * Input

     * @param array|Traversable $input
     *
     * @return mixed[]|false
     */
    public function input($input)
    {
        $output = [];

        foreach ($input as $symbol) {
            if (!$this->stepRunner->can($symbol, $transition)) {
                return false;
            }

            $output[] = $this->stepRunner->apply($symbol, $transition);
        }

        return $output;
    }

    /**
     * @return callable
     */
    public function getLambda()
    {
        return [$this, 'input'];
    }
}
