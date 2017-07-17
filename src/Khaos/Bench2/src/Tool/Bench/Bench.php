<?php

namespace Khaos\Bench2\Tool\Bench;

use Exception;
use Khaos\Bench2\BenchApplication;
use Khaos\Bench2\Expression;
use Khaos\Bench2\Tool\Bench\Help\ContextualHelpBuilder;
use Khaos\Bench2\Tool\Bench\Resource\Event\Event;
use Khaos\Bench2\Tool\Bench\Resource\Listener\Listener;
use Khaos\Bench2\Tool\Console\Console;
use Khaos\Bench2\Tool\Twig\Twig;
use Khaos\Console\Usage\Input;
use Khaos\Schema\SchemaInstanceRepository;

class Bench
{
    /**
     * @var BenchApplication
     */
    private $app;

    /**
     * @var Console
     */
    private $console;

    /**
     * @var Twig
     */
    private $twig;

    /**
     * @var SchemaInstanceRepository
     */
    private $resources;

    /**
     * @var string
     */
    private $cwd;

    /**
     * @var Expression
     */
    private $expression;

    /**
     * Bench constructor.
     *
     * @param BenchApplication $app
     * @param Console $console
     * @param Twig $twig
     */
    public function __construct(BenchApplication $app, Console $console, Twig $twig)
    {
        $this->console    = $console;
        $this->twig       = $twig;
        $this->app        = $app;
        $this->resources  = $app->getResources();
        $this->cwd        = $app->getBenchRoot();
        $this->expression = $app->getExpressionHandler();
    }

    public function chdir($dir)
    {
        throw new Exception();
    }

    public function cwd()
    {
        return $this->cwd;
    }

    public function dispatch($eventName)
    {
        /**
         * @var Event $event
         * @var Listener[] $listeners
         */

        $event     = $this->get('event:'.$eventName);
        $listeners = $this->query('listener', ['event' => $event->getId()]);

        foreach ($listeners as $listener)
            $listener();
    }

    public function evaluate($expression, $values = [])
    {
        return $this->expression->evaluate($expression, $values);
    }

    public function get($id)
    {
        return $this->resources->get($id);
    }

    public function help(Input $input)
    {
        (new ContextualHelpBuilder($this, $this->console, $this->twig))->build($input);
    }

    public function root()
    {
        return $this->app->getBenchRoot();
    }

    public function query($schema, $query = [])
    {
        return $this->resources->findBySchema($schema, $query);
    }

    public function version()
    {
        $this->console->write('Bench Version 1.0.0');
    }
}