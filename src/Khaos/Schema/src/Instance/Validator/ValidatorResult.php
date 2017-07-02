<?php

namespace Khaos\Schema\Instance\Validator;

class ValidatorResult
{
    /**
     * @var ValidatorResultLogger
     */
    private $logger;

    /**
     * ValidatorResult constructor.
     *
     * @param ValidatorResultLogger $logger
     */
    public function __construct(ValidatorResultLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     *
     */
    public function isValid()
    {
        return $this->logger->isValid();
    }
}