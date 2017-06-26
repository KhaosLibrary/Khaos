<?php

namespace Khaos\Bench\Tool\Bench\Resource\Command;

use Khaos\Bench\Bench;
use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Type\TypeRepository;
use Khaos\Bench\Tool\Console\ConsoleToolOperationProxy;
use Khaos\Console\Usage\Input;
use Khaos\Console\Usage\Model\OptionDefinitionRepository;
use Khaos\Console\Usage\Parser\OptionDefinitionParser;

/**
 * Class BenchCommandDefinition
 *
 */
class BenchCommandDefinition implements Definition
{
    /**
     * @var array
     */
    private $_data;

    /**
     * @var TypeRepository
     */
    private $_type;

    /**
     * DynamicDefinition constructor.
     *
     * @param array         $data           Data which will make up this definition
     * @param TypeRepository  $typeResolver   Used to match and return values
     */
    public function __construct(array $data, TypeRepository $typeResolver)
    {
        $this->_data = $data;
        $this->_type = $typeResolver;
    }

    /**
     * @inheritdoc
     */
    public function __get($key)
    {
        return
            $this->{$key} = $this->_type->{BenchCommandSchema::SCHEMA['map'][$key]['type']}->value(
                BenchCommandSchema::SCHEMA['map'][$key],
                $this->_data[$key],
                $this
            );
    }

    public function getUsage()
    {
        return $this->_data['definition']['usage'];
    }

    /**
     * @return OptionDefinitionRepository
     */
    public function getOptions()
    {
        $optionRepository = new OptionDefinitionRepository();
        $optionParser     = new OptionDefinitionParser();

        foreach ($this->_data['definition']['options'] as $option)
            $optionRepository->add($optionParser->parse($option));

        return $optionRepository;
    }

    public function getCommand()
    {
        return $this->{'definition'}->{'command'};
    }

    public function getTitle()
    {
        return $this->{'metadata'}->{'title'};
    }

    public function getDescription()
    {
        return $this->{'metadata'}->{'description'};
    }

    public function getWorkingDirectory()
    {
        return $this->{'metadata'}->{'working-directory'};
    }

    public function getSourceFile()
    {
        return $this->{'metadata'}->{'source-file'};
    }

    public function run(Bench $bench, Input $input)
    {
        $expressionHandler = $bench->getExpressionHandler();

        $values          = $expressionHandler->getGlobalValues();
        $values['input'] = $input;

        foreach ($this->definition->tasks as $task) {
            $expressionHandler->evaluate($task->run, $values);
        }
    }

    /**
     * @inheritdoc
     */
    public function match($match)
    {
        return $this->_type->{BenchCommandSchema::SCHEMA['type']}->match(BenchCommandSchema::SCHEMA, $match, $this);
    }

    /**
     * @return string
     */
    public function export()
    {
        return $this->_type->{BenchCommandSchema::SCHEMA['type']}->export(BenchCommandSchema::SCHEMA, $this->_data);
    }
}