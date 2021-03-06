<?php

namespace Test\Khaos\FSM\Runner;

use Khaos\FSM\Runner\BacktrackingRunner;
use Khaos\FSM\Context;
use Khaos\FSM\State\DefaultState;
use Khaos\FSM\State\State;
use PHPUnit_Framework_TestCase;

class BacktrackingRunnerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var BacktrackingRunner
     */
    private $fsm;

    protected function setUp()
    {
        $s1 = new DefaultState('S1', State::TYPE_INITIAL);
        $s2 = new DefaultState('S2');
        $s3 = new DefaultState('S3');
        $s4 = new DefaultState('S4');
        $s5 = new DefaultState('S5', State::TYPE_TERMINAL);

        $s1->addTransition('t1', $s2);
        $s1->addTransition('t1', $s3);
        $s2->addTransition('t2', $s4);
        $s3->addTransition('t2', $s5);

        $this->fsm = new BacktrackingRunner($s1, new Context());
    }

    /**
     * @test
     */
    public function it_finds_the_correct_path_to_reach_a_terminal_state_given_valid_input()
    {
        $this->assertEquals(['S3', 'S5'], $this->fsm->input(['t1', 't2']));
    }

    /**
     * @test
     */
    public function it_returns_false_given_invalid_input()
    {
        $this->assertEquals(false, $this->fsm->input(['t1', 't3']));
    }

    /**
     * @test
     */
    public function it_must_end_on_a_terminal_state_to_return_result()
    {
        $this->assertEquals(['S3', 'S5'], $this->fsm->input(['t1', 't2']));
        $this->assertEquals(false, $this->fsm->input(['t1']));
    }
}