<?php

namespace Khaos\Console\Application;

use Aura\Di\ContainerBuilder;
use Khaos\Console\Application\_Config\Common;
use Khaos\Console\Application\Event\InvalidUsageEvent;
use Khaos\Console\Application\Plugin\Plugin;
use Khaos\Console\Usage\Parser\OptionDefinitionParser;
use Khaos\Console\Usage\Parser\UsageParserBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Application
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $version;

    /**
     * @var Context
     */
    private $root;

    /**
     * @var Context[]
     */
    private $contexts = [];
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    const EVENT_INVALID_USAGE = 'khaos.console.application.invalid_usage';
    const EVENT_BEFORE_ACTION = 'khaos.console.application.before_action';

    /**
     * @var ContextFactory
     */
    private $contextFactory;

    /**
     * Application
     *
     * @param string $name
     * @param string $version
     * @param EventDispatcher $eventDispatcher
     * @param ContextFactory $contextFactory
     * @param Plugin[] $plugins
     */
    public function __construct($name, $version, EventDispatcher $eventDispatcher = null, ContextFactory $contextFactory = null, array $plugins = [])
    {
        $this->name        = $name;
        $this->version     = $version;
        $this->dispatcher  = ($eventDispatcher)?:new EventDispatcher();

        $this->contextFactory = ($contextFactory)?:new ContextFactory($this->dispatcher, new OptionDefinitionParser(), new UsageParserBuilder());
        $this->contextFactory->setDefaultRoot($this->root = $this->contextFactory->create($name));

        $this->contexts[$name] = ['instance' => $this->root, 'children' => []];

        foreach ($plugins as $plugin) {
            $plugin->setup($this);
        }
    }

    /**
     * Create Application
     *
     * @param string   $name
     * @param string   $version
     * @param string[] $config  Array of classes extending the Aura.Di Config class
     *
     * @return Application
     */
    public static function create($name, $version, $config = [])
    {
        $config = array_merge(
            [
                Common::class
            ],
            $config
        );

        return (new ContainerBuilder)->newInstance([], $config)->newInstance(
            self::class,
            [
                'name'    => $name,
                'version' => $version
            ]
        );
    }

    public function getRootContext()
    {
        return $this->root;
    }

    public function getContexts()
    {
        return $this->contexts;
    }

    /**
     * Context
     *
     * @param string $context
     *
     * @return Context
     */
    public function context($context)
    {
        if (!isset($this->contexts[$context])) {
            $current = &$this->contexts[$this->name];
            $key = $this->name;

            foreach (array_slice(explode(' ', $context), 1) as $part) {
                $key .= ' ' . $part;

                if (!isset($this->contexts[$key])) {
                    $this->contexts[$key] = [
                        'instance' => $current['children'][$part] = $this->contextFactory->create($key),
                        'children' => []
                    ];
                }

                $current = &$this->contexts[$key];
            }
        }

        return $this->contexts[$context]['instance'];
    }

    /**
     * @param $description
     *
     * @return $this
     */
    public function description($description)
    {
        $this->root->description($description);
        return $this;
    }

    /**
     * @param $usage
     *
     * @return $this
     */
    public function usage($usage)
    {
        $this->root->usage($usage);
        return $this;
    }

    /**
     * @param $option
     *
     * @return $this
     */
    public function option($option)
    {
        $this->root->option($option);
        return $this;
    }

    /**
     * @param $action
     *
     * @return $this
     */
    public function action($action)
    {
        $this->root->action($action);
        return $this;
    }

    /**
     * @param $event
     * @param $listener
     * @return $this
     */
    public function on($event, $listener)
    {
        $this->dispatcher->addListener($event, $listener);
        return $this;
    }

    public function run($args = null)
    {
        global $argv;

        if ($args === null) {
            $args    = $argv;
            $args[0] = $this->name;
        }

        if ($this->root->run($args)) {
            return;
        }

        foreach ($this->contexts as $context) {
            if ($context['instance']->run($args)) {
                return;
            }
        }

        $this->dispatcher->dispatch(self::EVENT_INVALID_USAGE, new InvalidUsageEvent($args, $this->root->getOptionDefinitions()));
    }
}
