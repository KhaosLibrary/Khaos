<?php

namespace Khaos\Schema\Instance\Validator\TypeValidator;

/**
 * Class TypeValidatorCollection
 *
 * @package Khaos\Schema
 */
class TypeValidatorCollection
{
    /**
     * @var TypeValidator[]
     */
    private $typeValidators = [];

    /**
     * Add Type Validator
     *
     * @param TypeValidator $typeValidator
     *   Type validator to be added to the collection.
     *
     * @return void
     */
    public function add(TypeValidator $typeValidator)
    {
        $this->typeValidators[$typeValidator->getName()] = $typeValidator;
    }

    /**
     * Get Type Validator
     *
     * @param string $type
     *   The name of the type to get.
     *
     * @return TypeValidator|null
     */
    public function get($type)
    {
        if (!isset($this->typeValidators[$type]))
            return null;

        return $this->typeValidators[$type];
    }
}