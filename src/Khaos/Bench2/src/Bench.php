<?php

namespace Khaos\Bench2;

use Exception;
use Khaos\Bench2\Events\WorkspaceResourcesLoadedEvent;
use Khaos\Bench2\Schema\Keyword\ExpressionKeyword;
use Khaos\Bench2\Tool\Bench\Resource\Command\Command;
use Khaos\Bench2\Tool\Bench\Resource\Command\CommandSchema;
use Khaos\Bench2\Tool\ToolPackageRepository;
use Khaos\Console\Usage\Parser\UsageParserBuilder;
use Khaos\Schema\FileDataProvider;
use Khaos\Schema\InstanceFactoryCollection;
use Khaos\Schema\SchemaInstanceRepository;
use Khaos\Schema\SchemaInstanceValidator;
use Khaos\Schema\SchemaRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Bench implements EventSubscriberInterface
{
    /**
     * @var SchemaInstanceRepository
     */
    private $resources;

    /**
     * @var ToolPackageRepository
     */
    private $tools;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * Enabled Tools
     *
     * @var bool[]
     */
    private $enabledTools = [];

    /**
     * @var string
     */
    private $workingDirectory;

    /**
     * @var Expression
     */
    private $expression;

    /**
     * Bench constructor.
     *
     * @param ToolPackageRepository $tools
     * @param string $workingDirectory
     * @param SchemaInstanceRepository $resources
     * @param EventDispatcher $eventDispatcher
     * @param Expression $expression
     */
    public function __construct(
        ToolPackageRepository $tools,
        string $workingDirectory               = null,
        SchemaInstanceRepository $resources    = null,
        EventDispatcher $eventDispatcher       = null,
        Expression $expression = null
    )
    {
        $this->tools            = $tools;
        $this->workingDirectory = $workingDirectory ?? self::getWorkingDirectory(getcwd());
        $this->resources        = $resources ?? $this->prepareInstanceSchemaRepository();
        $this->dispatcher       = $eventDispatcher ?? new EventDispatcher();
        $this->expression       = $expression ?? new Expression();

        $this->enable('bench');
    }

    /**
     * Enable Tool
     *
     * @param string $tool
     */
    public function enable($tool)
    {
        if (isset($this->enabledTools[$tool]))
            return;

        $package = $this->tools->get($tool);
        $package->setBench($this);

        $this->resources->addSchemaProvider($package->getSchemaProvider());
        $this->dispatcher->addSubscriber($package->getSubscriber());

        if (($commandProxy = $package->getCommandProxy()) !== null)
            $this->expression->addValue($tool, $commandProxy);

        $this->enabledTools[$tool] = true;

        foreach ($package->getDependencies() as $dependency)
            $this->enable($dependency);
    }

    /**
     * Import
     *
     * @param string $file
     */
    public function import($file)
    {
        if (!file_exists($file))
            $file = $this->workingDirectory.'/'.$file;

        $this->resources->addDataProvider(new FileDataProvider($file));
    }

    /**
     * Run

     * @param array $args
     */
    public function run($args)
    {
        $this->dispatcher->dispatch(WorkspaceResourcesLoadedEvent::NAME, new WorkspaceResourcesLoadedEvent($this->resources));

        $args[0] = 'bench';

        $usage = new UsageParserBuilder();
        $input = false;

        foreach ($this->resources->findBySchema(CommandSchema::NAME) as $command)
        {
            /** @var Command $command */

            $parser = $usage->createUsageParser($command->getUsage(), $command->getOptions());

            if (($input = $parser->parse($args)) !== false)
            {
                $command->run($input);
                break;
            }
        }

        if (!$input)
            echo 'Command Not Found!';
    }

    /**
     * @return SchemaInstanceRepository
     */
    private function prepareInstanceSchemaRepository()
    {
        $schemaInstanceValidator = new SchemaInstanceValidator();
        $schemaCollection        = new SchemaRepository();

        $schemaInstanceValidator
            ->addKeyword(new ExpressionKeyword());

        return new SchemaInstanceRepository(
            $schemaInstanceValidator,
            $schemaCollection
        );
    }

    /**
     * @param $search
     * @param string $file
     * @return string
     * @throws Exception
     */
    public static function getWorkingDirectory($search, $file = 'bench.yml')
    {
        $search = $search.DIRECTORY_SEPARATOR;
        $length = strlen($search) + 1;
        $offset = $length;

        while (($offset = strrpos($search, DIRECTORY_SEPARATOR, $offset - $length)) !== false)
        {
            if (file_exists($candidate = substr($search, 0, $offset).DIRECTORY_SEPARATOR.$file))
                return dirname($candidate);
        }

        throw new Exception('bench.yml could be found.');
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
    }

    /**
     * @return Expression
     */
    public function getExpressionHandler()
    {
        return $this->expression;
    }
}