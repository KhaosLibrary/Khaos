<?php

namespace Khaos\Bench\Tool\Bench;

use Khaos\Bench\Bench;
use Khaos\Bench\BenchRunEvent;
use Khaos\Bench\CacheDefinitionsEvent;
use Khaos\Bench\PrepareExpressionHandlerEvent;
use Khaos\Bench\Resource\Type\Expression\ExpressionHandler;
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
     * @var ExpressionHandler
     */
    private $expressionHandler;

    /**
     * BenchTool constructor.
     *
     * @param Bench              $bench
     * @param ExpressionHandler  $expressionHandler
     */
    public function __construct(Bench $bench, ExpressionHandler $expressionHandler)
    {
        $this->bench             = $bench;
        $this->operationProxy    = new BenchToolOperationProxy($bench);
        $this->expressionHandler = $expressionHandler;
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

            $this->expressionHandler->addGlobalValue('input', $input);

            $commandDefinition->run($this->bench, $input);
        }
        else
        {
            foreach ($definitionRepository->query(['schema' => 'bench/command']) as $definitionKey => $commandDefinition)
            {
                $parser = $usageParserBuilder->createUsageParser($commandDefinition->getUsage(), $commandDefinition->getOptions());

                if (($input = $parser->parse($args)) !== false)
                {
                    $this->expressionHandler->addGlobalValue('input', $input);

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
            'get',
            function($id)
            {
                return '$bench->get('.$id.')';
            },
            function($arguments, $id)
            {
                return $this->operationProxy->get($id);
            }
        );

        $expressionHandler->register(
            'query',
            function($match)
            {
                return '$bench->query('.var_export($match, true).')';
            },
            function($arguments, $match)
            {
                return $this->operationProxy->query($match);
            }
        );

        $expressionHandler->register(
            'file',
            function($file)
            {
                return '$bench->file('.$file.')';
            },
            function($arguments, $file)
            {
                return $this->operationProxy->file($file);
            }
        );

        $expressionHandler->register(
            'decrypt',
            function($data)
            {
                return '$bench->decrypt('.$data.')';
            },
            function($arguments, $data)
            {
                return $this->operationProxy->decrypt($data);
            }
        );

        $expressionHandler->register(
            'encrypt',
            function($data)
            {
                return '$bench->encrypt('.$data.')';
            },
            function($arguments, $data)
            {
                return $this->operationProxy->encrypt($data);
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

        $bench->import(__DIR__.'/_config/definition/namespace/secret.yml');
        $bench->import(__DIR__.'/_config/definition/command/decrypt.yml');
        $bench->import(__DIR__.'/_config/definition/command/encrypt.yml');
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