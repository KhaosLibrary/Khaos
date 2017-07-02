<?php

namespace Khaos\Schema\Instance\Validator\TypeValidator;

use Khaos\Schema\Instance\Validator\RecursiveValidator;
use Khaos\Schema\Instance\Validator\ValidatorResultLogger;

class StringTypeValidator implements TypeValidator
{
    const NAME = 'string';

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
        if (!is_string($instance))
            $logger->invalid(sprintf('expected a string but instead got a "%s".', gettype($instance)));

        $logger->valid();
    }
}