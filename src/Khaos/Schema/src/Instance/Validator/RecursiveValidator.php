<?php

namespace Khaos\Schema\Instance\Validator;

use Khaos\Schema\Instance\Validator\TypeValidator\TypeValidatorCollection;

class RecursiveValidator
{
    /**
     * Collection of {@link TypeValidator}
     *
     * @var TypeValidatorCollection
     */
    private $type;

    /**
     * RecursiveValidator constructor.
     *
     * @param TypeValidatorCollection $type
     *   Types which should be supported when validating.
     */
    public function __construct(TypeValidatorCollection $type)
    {
        $this->type = $type;
    }

    /**
     * Validate Instance against Schema
     *
     * Recursively validated the instance against the given schema, as sub-schemas are encountered
     * the result logger is updated.
     *
     * The result logger can be used directly after completion to determine validity of instance or
     * wrapped in a {@link ValidatorResult} object to provide a simplified interface.
     *
     * @param array $schema
     *   Schema against which the instance will be validated.
     *
     * @param $instance
     *   Instance to be validated.
     *
     * @param ValidatorResultLogger $logger
     *   Detailed log of the validation to aid in debugging and general error reporting.
     *
     * @return void
     *   All required information is held in the result logger.
     */
    public function validate(array $schema, $instance, ValidatorResultLogger $logger)
    {
        if (!isset($schema['type'])) {
            $logger->invalid("keyword 'type' expected but not found.");
            return;
        }

        if (!($typeValidator = $this->type->get($schema['type']))) {
            $logger->invalid(sprintf("type '%s' is not available.", $schema['type']));
            return;
        }

        $typeValidator->validate($schema, $instance, $logger, $this);
    }
}