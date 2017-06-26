<?php

use Khaos\Bench\Bench;
use Khaos\Bench\PrepareExpressionHandlerEvent;
use Khaos\Bench\Registry;
use Khaos\Bench\Resource\Definition\DefaultDefinitionRepositoryFactory;
use Khaos\Bench\Resource\Loader\FileLoader;
use Khaos\Bench\Resource\Loader\Loader;
use Khaos\Bench\Resource\Loader\Yaml\Parser;
use Khaos\Bench\Resource\Loader\YamlLoader;
use Khaos\Bench\Tool\Bench\BenchTool;
use Khaos\Bench\Tool\Bench\Resource\Command\BenchCommandSchema;
use Khaos\Bench\Resource\Schema\DefaultSchemaRepository;
use Khaos\Bench\Resource\Type\DefaultTypeRepository;
use Khaos\Bench\Resource\Type\Expression\ExpressionHandler;
use Khaos\Bench\Resource\Type\Expression\ExpressionType;
use Khaos\Bench\Resource\Type\Expression\StandardExpressionHandler;
use Khaos\Bench\Resource\Type\Map\MapType;
use Khaos\Bench\Resource\Type\Sequence\SequenceType;
use Khaos\Bench\Resource\Type\String\StringType;
use Khaos\Bench\Tool\Bench\Resource\CommandNamespace\BenchCommandNamespaceSchema;
use Khaos\Bench\Tool\Console\ConsoleTool;
use Khaos\Bench\Tool\Docker\DockerTool;
use Khaos\Bench\Tool\StandardToolRepository;
use Khaos\Bench\Tool\Template\TemplateTool;
use Khaos\Bench\Tool\Twig\TwigTool;
use Khaos\Cache\FileCacheItemPool;
use Khaos\Console\Usage\Parser\UsageParserBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Yaml\Parser as SymfonyYamlParser;

require_once __DIR__.'/../../../../vendor/autoload.php';

/*
 * Environment Constants
 */

define('BENCH_WORKING_DIRECTORY', Bench::getWorkingDirectory(getcwd()));

/*
 * Symfony\Component\EventDispatcher\EventDispatcher
 */

$eventDispatcher = new EventDispatcher();

$registry = new Registry();

/*
 * Khaos\Bench\Resource\Type\Expression\ExpressionHandler
 *
 * Not always needed and quite heavy to initialise so lets lazy load it when
 * needed.
 */

$lazyLoadedExpressionHandler = new class($eventDispatcher) implements ExpressionHandler
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __get($key)
    {
        return $this->standardExpressionHandler = new StandardExpressionHandler($this->eventDispatcher);
    }

    public function compile($expression, $names = [])
    {
        return $this->standardExpressionHandler->compile($expression, $names);
    }

    public function evaluate($expression, $values = [])
    {
        return $this->standardExpressionHandler->evaluate($expression, $values);
    }

    public function register($name, callable $compiler, callable $evaluator)
    {
        return $this->standardExpressionHandler->register($name, $compiler, $evaluator);
    }

    public function addGlobalValue($name, $value)
    {
        return $this->standardExpressionHandler->addGlobalValue($name, $value);
    }

    public function getGlobalValues()
    {
        return $this->standardExpressionHandler->getGlobalValues();
    }
};

/*
 * Khaos\Bench\Resource\Loader\Yaml\Parser
 *
 * Not always needed and quite heavy to initialise to lets lazy load it when
 * needed.
 */

$lazyLoadedYamlParser = new class implements Parser
{
    public function parse($value)
    {
        return $this->symfonyYamlParser->parse($value);
    }

    public function __get($key)
    {
        return $this->symfonyYamlParser = new SymfonyYamlParser();
    }
};

/*
 * Khaos\Bench\Resource\Loader\Loader
 *
 * Only needed when the cache is not present so lazy load it to save needlessly
 * loading and initialising.
 */

$lazyLoadedDefinitionLoader = new class($lazyLoadedYamlParser) implements Loader
{
    private $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function __get($key)
    {
        $fileLoader = new FileLoader();
        $fileLoader->add(['yaml', 'yml'], new YamlLoader($this->parser));

        return $this->fileLoader = $fileLoader;
    }

    public function load($source)
    {
        return $this->fileLoader->load($source);
    }
};

/*
 * Khaos\Bench\Resource\Type\DefaultTypeRepository
 *
 *  - map
 *  - string
 *  - sequence
 *  - expression
 */

$typeRepository = new DefaultTypeRepository();

$typeRepository->add(new MapType($typeRepository));
$typeRepository->add(new StringType($typeRepository));
$typeRepository->add(new SequenceType($typeRepository));
$typeRepository->add(new ExpressionType($typeRepository, $lazyLoadedExpressionHandler, $registry));

/*
 * Khaos\Bench\Resource\Schema\DefaultSchemaRepository
 *
 *  - command
 */

$schemaRepository = new DefaultSchemaRepository();
$schemaRepository->add(new BenchCommandSchema($typeRepository));
$schemaRepository->add(new BenchCommandNamespaceSchema($typeRepository));

/*
 * Khaos\Bench\Resource\Definition\DefaultDefinitionRepositoryFactory
 */

$definitionRepositoryFactory = new DefaultDefinitionRepositoryFactory($typeRepository, $schemaRepository);

/*
 * Khaos\Cache\FileCacheItemPool
 */

$cachePool = new FileCacheItemPool(BENCH_WORKING_DIRECTORY.'/.bench/cache');

/*
 * Khaos\Console\Usage\Parser\UsageParserBuilder
 */

$usageParserBuilder = new UsageParserBuilder();


/*
 * Khaos\Bench\Tool\StandardToolRepository
 */

$toolRepository = new StandardToolRepository();

/*
 * All Dependencies Setup
 *
 * Create and return a new instance of bench.
 */

$bench = new Bench(
    BENCH_WORKING_DIRECTORY,
    $cachePool,
    $lazyLoadedDefinitionLoader,
    $definitionRepositoryFactory,
    $eventDispatcher,
    $toolRepository,
    $registry,
    $lazyLoadedExpressionHandler
);

$benchTool   = BenchTool::create($bench);
$consoleTool = ConsoleTool::create($bench);
$dockerTool  = DockerTool::create($bench);
$twigTool    = TwigTool::create($bench);

$toolRepository->add($benchTool);
$toolRepository->add($consoleTool);
$toolRepository->add($dockerTool);
$toolRepository->add($twigTool);

$eventDispatcher->addSubscriber($benchTool);
$eventDispatcher->addSubscriber($consoleTool);
$eventDispatcher->addSubscriber($dockerTool);
$eventDispatcher->addSubscriber($twigTool);

return $bench;
