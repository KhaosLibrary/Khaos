<?php

namespace Khaos\Schema\Instance\Validator\TypeValidator;

use Khaos\Schema\Instance\Validator\RecursiveValidator;
use Khaos\Schema\Instance\Validator\ValidatorResultLogger;

interface TypeValidator
{
    /**
     * Name of the type validator
     *
     * @return string
     */
    public function getName();

    /**
     * Validate Instance against Schema Type
     *
     * @param array $schema
     *   Schema against which the instance will be validated.
     *
     * @param mixed $instance
     *   Instance to be validated.
     *
     * @param ValidatorResultLogger $logger
     *   Detailed log of the validation to aid in debugging and general error reporting.
     *
     * @param RecursiveValidator $validator
     *   Validator to use when sub-schemas are encountered.
     *
     * @return void
     *   All required information is held in the result logger
     */
    public function validate($schema, $instance, ValidatorResultLogger $logger, RecursiveValidator $validator);
}