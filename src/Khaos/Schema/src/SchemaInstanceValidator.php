<?php

namespace Khaos\Schema;

use Khaos\Schema\Instance\Validator\RecursiveValidator;
use Khaos\Schema\Instance\Validator\ValidatorResult;
use Khaos\Schema\Instance\Validator\ValidatorResultLogger;

/**
 * Class SchemaInstanceValidator
 *
 * Validates instances against a given schema.
 *
 * @package Khaos\Schema
 */
class SchemaInstanceValidator
{
    /**
     * @var RecursiveValidator
     */
    private $validator;

    /**
     * SchemaInstanceValidator constructor.
     *
     * @param RecursiveValidator $validator
     *   Does the real work of validating the schema and building the result.
     */
    public function __construct(RecursiveValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate instance against schema
     *
     * @param array $schema
     * @param mixed $instance
     *
     * @return ValidatorResult
     */
    public function validate($schema, $instance)
    {
        $this->validator->validate($schema, $instance, $logger = new ValidatorResultLogger());

        return new ValidatorResult($logger);
    }
}