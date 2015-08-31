<?php

namespace Khaos\Console\Usage\Model;

class Option
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var OptionDefinition
     */
    private $definition;

    /**
     * @var mixed|null
     */
    private $value;

    /**
     * Option
     *
     * @param string           $name
     * @param OptionDefinition $definition
     * @param mixed|null       $value
     */
    public function __construct($name, OptionDefinition $definition, $value = null)
    {
        $this->name       = $name;
        $this->definition = $definition;
        $this->value      = $value;
    }

    /**
     * Get Definition
     *
     * @return OptionDefinition
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Value
     *
     * @return null|string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function __toString()
    {
        $value = $this->value;

        if (is_bool($value)) {
            $value = ($value) ? 'true' : 'false';
        }

        return 'option(\''.$this->name.'\', \''.$value.'\')';
    }
}
