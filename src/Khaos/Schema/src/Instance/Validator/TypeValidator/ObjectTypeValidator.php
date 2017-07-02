<?php

namespace Khaos\Schema\Instance\Validator\TypeValidator;

use Khaos\Schema\Instance\Validator\RecursiveValidator;
use Khaos\Schema\Instance\Validator\ValidatorResultLogger;

class ObjectTypeValidator implements TypeValidator
{
    const NAME = 'object';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @inheritdoc
     */
    public function validate($schema, $instance, ValidatorResultLogger $logger, RecursiveValidator $validator)
    {
        if (!is_object($instance))
            $logger->invalid(sprintf('expected an object but instead got a "%s".', gettype($instance)));

        foreach (array_keys(get_object_vars($instance)) as $property)
        {
            $logger->scope($property);

            $this->validateProperty($property, $schema, $instance, $logger, $validator);

            if (!$logger->isValid())
            {
                $logger->invalid(sprintf('Child property "%s" is invalid.', $property));
                return;
            }
        }

        $logger->valid();
    }

    /**
     * Validate Property
     *
     * @param string $property
     * @param array $schema
     * @param object $instance
     * @param ValidatorResultLogger $logger
     * @param RecursiveValidator $validator
     */
    private function validateProperty($property, $schema, $instance, ValidatorResultLogger $logger, RecursiveValidator $validator)
    {
        if (!isset($schema['properties'][$property])) {
            $logger->valid();
            return;
        }

        $validator->validate($schema['properties'][$property], $instance->{$property}, $logger);
    }
}