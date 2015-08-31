<?php

namespace Khaos\Console\Application\_Config;

use Aura\Di\Config;
use Aura\Di\Container;
use Khaos\Console\Application\Application;
use Khaos\Console\Application\ContextFactory;
use Khaos\Console\Application\Plugin\ContextualHelpPlugin;
use Khaos\Console\Application\Plugin\VersionInfoPlugin;
use Khaos\Console\Usage\Parser\OptionDefinitionParser;
use Khaos\Console\Usage\Parser\UsageParserBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->set('event_dispatcher', $di->lazyNew(EventDispatcher::class));

        $di->params[Application::class]['eventDispatcher'] = $di->lazyGet('event_dispatcher');
        $di->params[Application::class]['contextFactory']  = $di->lazyNew(ContextFactory::class);
        $di->params[Application::class]['plugins']         = $di->lazy(function () {
        
            $plugins = [];

            $plugins[] = new ContextualHelpPlugin();
            $plugins[] = new VersionInfoPlugin();

            return $plugins;
        });

        $di->params[ContextFactory::class]['eventDispatcher']        = $di->lazyGet('event_dispatcher');
        $di->params[ContextFactory::class]['optionDefinitionParser'] = $di->lazyNew(OptionDefinitionParser::class);
        $di->params[ContextFactory::class]['usageParserBuilder']     = $di->lazyNew(UsageParserBuilder::class);

    }
}
