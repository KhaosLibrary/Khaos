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
use Khaos\Schema\SchemaInstanceRepository;
use Khaos\Schema\SchemaInstanceValidator;
use Khaos\Schema\SchemaRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;

class BenchApplication
{
    /**
     * @var SchemaInstanceRepository
     */
    private $resources;

    /**
     * @var ToolPackageRepository
     */
    private $packages;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * Enabled Tools
     *
     * @var object[]
     */
    private $tools = [];

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
     * @param ToolPackageRepository $packages
     * @param string $cwd
     * @param SchemaInstanceRepository $resources
     * @param EventDispatcher $eventDispatcher
     * @param Expression $expression
     */
    public function __construct(ToolPackageRepository $packages, $cwd = null, SchemaInstanceRepository $resources = null, EventDispatcher $eventDispatcher = null, Expression $expression = null)
    {
        $this->packages    = $packages;
        $this->cwd         = $cwd ?? self::getWorkingDirectory(getcwd());
        $this->dispatcher  = $eventDispatcher ?? new EventDispatcher();
        $this->expression  = $expression ?? new Expression();
        $this->resources   = $resources;

        if ($this->resources == null)
        {
            $schemaInstanceValidator = new SchemaInstanceValidator();
            $schemaCollection        = new SchemaRepository();

            $schemaInstanceValidator
                ->addKeyword(new ExpressionKeyword());

            $this->resources = new SchemaInstanceRepository(
                $schemaInstanceValidator,
                $schemaCollection
            );
        }

        $this->enable('bench');
    }

    /**
     * Enable Tool
     *
     * @param string $toolName
     */
    public function enable($toolName)
    {
        if (isset($this->tools[$toolName]))
            return;

        $package = $this->packages->get($toolName);

        if (($schemaProvider = $package->getSchemaProvider($this)) !== null)
            $this->resources->addSchemaProvider($schemaProvider);

        if (($eventSubscriber = $package->getSubscriber($this)) !== null)
            $this->dispatcher->addSubscriber($eventSubscriber);

        if (($tool = $this->tools[$toolName] = $package->getTool($this)) !== null)
            $this->expression->addValue($toolName, $tool);

        if (($dependencies = $package->getDependencies()) !== null)
        {
            foreach ($dependencies as $dependency)
                $this->enable($dependency);
        }
    }

    /**
     * Get Tool
     *
     * @param string $toolName
     *
     * @return object
     *
     * @throws Exception
     */
    public function tool($toolName)
    {
        if (!isset($this->tools[$toolName]))
            throw new Exception();

        return $this->tools[$toolName];
    }

    /**
     * Import
     *
     * @param string $file
     */
    public function import($file)
    {
        if (!file_exists($file))
            $file = $this->cwd.'/'.$file;

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
                $this->expression->addValue('input', $input);
                $command->run($input);
                break;
            }
        }

        if (!$input)
            echo 'Command Not Found!';
    }

    /**
     * @return Expression
     */
    public function getExpressionHandler()
    {
        return $this->expression;
    }

    /**
     * @param $search
     * @param string $file
     *
     * @return string
     *
     * @throws Exception
     */
    public static function getWorkingDirectory($search, $file = 'bench.yaml')
    {
        $search = $search.DIRECTORY_SEPARATOR;
        $length = strlen($search) + 1;
        $offset = $length;

        while (($offset = strrpos($search, DIRECTORY_SEPARATOR, $offset - $length)) !== false)
        {
            if (file_exists($candidate = substr($search, 0, $offset).DIRECTORY_SEPARATOR.$file))
                return dirname($candidate);
        }

        throw new Exception('bench.yaml could be found.');
    }

    /**
     * @return SchemaInstanceRepository
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @return string
     */
    public function getBenchRoot()
    {
        return $this->cwd;
    }
}