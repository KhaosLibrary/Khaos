<?php

namespace Khaos\Console\Application\_Config;

use Aura\Di\Container;
use Khaos\Console\Application\Application;
use Khaos\Console\Application\ContextFactory;
use Khaos\Console\Application\DI\ContainerConfig;
use Khaos\Console\Usage\Parser\OptionDefinitionParser;
use Khaos\Console\Usage\Parser\UsageParserBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Common implements ContainerConfig
{
    /**
     * @inheritDoc
     */
    public function define(Container $di)
    {
        $di->set('event_dispatcher', $di->lazyNew(EventDispatcher::class));

        $di->params[Application::class]['eventDispatcher'] = $di->lazyGet('event_dispatcher');
        $di->params[Application::class]['contextFactory']  = $di->lazyNew(ContextFactory::class);

        $di->params[ContextFactory::class]['eventDispatcher']        = $di->lazyGet('event_dispatcher');
        $di->params[ContextFactory::class]['optionDefinitionParser'] = $di->lazyNew(OptionDefinitionParser::class);
        $di->params[ContextFactory::class]['usageParserBuilder']     = $di->lazyNew(UsageParserBuilder::class);
    }

    /**
     * @inheritDoc
     */
    public function modify(Container $di)
    {
        return;
    }
}
