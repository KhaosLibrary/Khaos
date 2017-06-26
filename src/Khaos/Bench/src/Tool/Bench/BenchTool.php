<?php

namespace Khaos\Bench\Tool\Bench;

use Khaos\Bench\Bench;
use Khaos\Bench\BenchRunEvent;
use Khaos\Bench\CacheDefinitionsEvent;
use Khaos\Bench\PrepareExpressionHandlerEvent;
use Khaos\Bench\Tool\Tool;
use Khaos\Console\Usage\Parser\UsageParserBuilder;

class BenchTool implements Tool
{
    const NAME = 'bench';

    /**
     * @var Bench
     */
    private $bench;

    /**
     * @var BenchToolOperationProxy
     */
    private $operationProxy;

    /**
     * BenchTool constructor.
     *
     * @param Bench $bench
     */
    public function __construct(Bench $bench)
    {
        $this->bench = $bench;
        $this->operationProxy = new BenchToolOperationProxy($bench);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * onBenchRun
     *
     * @param BenchRunEvent $event
     */
    public function onBenchRun(BenchRunEvent $event)
    {
        $input                = false;
        $args                 = $event->getArgs();
        $commandCache         = $event->getBench()->getCachePool()->get('usage-'.md5(implode(' ', $args)));
        $definitionRepository = $event->getBench()->getDefinitionRepository();
        $usageParserBuilder   = new UsageParserBuilder();

        if ($commandCache->isHit())
        {
            $commandDefinition = $definitionRepository->{$commandCache->value()};
            $parser            = $usageParserBuilder->createUsageParser($commandDefinition->getUsage(), $commandDefinition->getOptions());
            $input             = $parser->parse($args);

            $commandDefinition->run($this->bench, $input);
        }
        else
        {
            foreach ($definitionRepository->query(['schema' => 'bench/command']) as $definitionKey => $commandDefinition)
            {
                $parser = $usageParserBuilder->createUsageParser($commandDefinition->getUsage(), $commandDefinition->getOptions());

                if (($input = $parser->parse($args)) !== false)
                {
                    $commandCache->set(var_export($definitionKey, true));
                    $commandDefinition->run($this->bench, $input);
                    break;
                }
            }
        }

        if (!$input) {
            echo 'Command Not Found';
        }
    }

    public function onPrepareExpressionHandler(PrepareExpressionHandlerEvent $event)
    {
        $expressionHandler = $event->getExpressionHandler();
        $expressionHandler->addGlobalValue('bench', $this->getOperationProxy());

        $expressionHandler->register(
            'tool',
            function($tool)
            {
                return '$bench->tool('.$tool.')';
            },
            function($arguments, $tool)
            {
                return $this->tool($tool);
            }
        );

        $expressionHandler->register(
            'get',
            function($id)
            {
                return '$bench->getDefinitionRepository()->{'.$id.'}';
            },
            function($arguments, $id)
            {
                return $this->bench->getDefinitionRepository()->{$id};
            }
        );

        $expressionHandler->register(
            'query',
            function($match)
            {
                return '$bench->getDefinitionRepository()->query('.var_export($match, true).')';
            },
            function($arguments, $match)
            {
                return $this->bench->getDefinitionRepository()->query($match);
            }
        );
    }

    /**
     * @param CacheDefinitionsEvent $event
     */
    public function onCacheDefinitions(CacheDefinitionsEvent $event)
    {
        $bench = $event->getBench();

        $bench->import(__DIR__.'/_config/definition/namespace/bench.yml');
        $bench->import(__DIR__.'/_config/definition/command/help.yml');
        $bench->import(__DIR__.'/_config/definition/command/version.yml');
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            BenchRunEvent::NAME                  => 'onBenchRun',
            PrepareExpressionHandlerEvent::NAME  => 'onPrepareExpressionHandler',
            CacheDefinitionsEvent::NAME          => 'onCacheDefinitions',
        ];
    }

    /**
     * Create Instance of Tool
     *
     * @param Bench $bench
     *
     * @return Tool
     */
    public static function create(Bench $bench)
    {
        return new self($bench);
    }

    /**
     * @return mixed
     */
    public function getOperationProxy()
    {
        return $this->operationProxy;
    }
}