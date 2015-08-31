<?php

namespace Test\Khaos\Console\Application;

use Khaos\Console\Application\Application;
use Khaos\Console\Application\Event\BeforeActionEvent;
use Khaos\Console\Usage\Input;
use PHPUnit_Framework_TestCase;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    /**
     * Ensures that by default [options] is added to usage
     * definitions.
     *
     * @test
     */
    public function options_are_added_to_usage_definitions_by_default()
    {
        $application = (new Application('foo', '1.0'))
            ->option('-d, --debug')
            ->option('-V, --verbose')
            ->action(function(Input $input)
            {
                $this->assertSame(false, $input->getOption('debug'));
                $this->assertSame(false, $input->getOption('verbose'));
            });

        $application->run(['foo']);
    }

    /**
     * @test
     */
    public function when_no_usage_is_specified_action_is_still_triggered_by_usage_matching_context()
    {
        $triggered = false;

        $application = (new Application('foo', '1.0'))
            ->context('foo bar')
            ->action(function() use (&$triggered)
            {
                $triggered = true;
            });

        $application->run(['foo', 'bar']);

        $this->assertTrue($triggered);
    }

    /**
     * @test
     */
    public function invalid_usage_event_dispatched_when_no_usage_match_found()
    {
        $triggered = false;

        $application = (new Application('foo', '1.0'))
            ->usage('foo bar')
            ->action(function() { $this->fail('I should not be able to get here.'); });

        $application->on(Application::EVENT_INVALID_USAGE, function () use (&$triggered) { $triggered = true; } );
        $application->run([]);

        $this->assertTrue($triggered);
    }

    /**
     * @test
     */
    public function event_triggered_before_context_actions_invoked()
    {
        $triggered = false;

        $application = (new Application('foo', '1.0'))
            ->usage('foo bar')
            ->action(function() {});

        $application->on(Application::EVENT_BEFORE_ACTION, function () use (&$triggered) { $triggered = true; });
        $application->run(['foo', 'bar']);

        $this->assertTrue($triggered);
    }

    /**
     * @test
     */
    public function context_actions_can_be_stopped_from_running()
    {
        $triggered = false;

        $application = (new Application('foo', '1.0'))
            ->usage('foo bar')
            ->action(function() use (&$triggered) { $triggered = true; });

        $application->on(Application::EVENT_BEFORE_ACTION, function(BeforeActionEvent $event) { $event->preventAction(); });
        $application->run(['foo', 'bar']);

        $this->assertFalse($triggered);
    }
}