<?php

namespace Khaos\Console\Usage\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;

class OptionDefinitionRepository implements IteratorAggregate, Countable
{
    private $options = [];

    /**
     * @var OptionDefinition[]
     */
    private $map = [];

    /**
     * Add Option Definition
     *
     * @param OptionDefinition $optionDefinition
     *
     * @return void
     */
    public function add(OptionDefinition $optionDefinition)
    {
        $this->options[$optionDefinition->getLabel()] = $optionDefinition;

        if (($shortName = $optionDefinition->getShortName()) !== null) {
            $this->map[$shortName] = $optionDefinition;
        }

        if (($longName = $optionDefinition->getLongName()) !== null) {
            $this->map[$longName] = $optionDefinition;
        }
    }

    /**
     * Find Option Definition
     *
     * @param string $optionName
     *
     * @return OptionDefinition|null
     */
    public function find($optionName)
    {
        return (isset($this->map[$optionName])) ? $this->map[$optionName] : null;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->options);
    }

    /**
     * Merge
     *
     * @param OptionDefinitionRepository $toMerge
     *
     * @return OptionDefinitionRepository
     */
    public function merge(OptionDefinitionRepository $toMerge)
    {
        $merged = clone $this;

        foreach ($toMerge->options as $option) {
            $merged->add($option);
        }

        return $merged;
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->options);
    }
}
