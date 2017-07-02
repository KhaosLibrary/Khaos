<?php

namespace Khaos\Schema\Instance\Validator;

class ValidatorResultLoggerFrame
{
    /**
     * @var ValidatorResultLoggerFrame
     */
    private $parent;

    /**
     * @var ValidatorResultLoggerFrame[]
     */
    private $children;

    private $validity;

    private $message;

    public function __construct(ValidatorResultLoggerFrame $parent = null)
    {
        $this->parent = $parent;
    }

    public function setValidity($validity)
    {
        $this->validity = $validity;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getScope($scope)
    {
        if (!isset($this->children[$scope]))
            $this->children[$scope] = new ValidatorResultLoggerFrame($this);

        return $this->children[$scope];
    }
}