<?php

namespace Khaos\Schema\Instance\Validator;

class ValidatorResultLogger
{
    const IS_VALID   = 0;
    const IS_NOTICE  = 1;
    const IS_WARNING = 2;
    const IS_INVALID = 3;

    /**
     * Current validity of instance
     *
     * @var int
     */
    private $validity = self::IS_VALID;


    private $rootScope;

    private $currentScope;


    public function __construct()
    {
        $this->rootScope    = new ValidatorResultLoggerFrame();
        $this->currentScope = $this->rootScope;
    }

    /**
     * Starts a new scope
     *
     * @param string $scope
     */
    public function scope($scope)
    {
        $this->currentScope = $this->currentScope->getScope($scope);
    }

    /**
     * End scope as valid
     *
     * @param string|null $message
     */
    public function valid($message = null)
    {
        $this->end(self::IS_VALID, $message);
    }

    /**
     * Ends the scope as valid but with a notice
     *
     * @param string $message
     */
    public function notice($message)
    {
        $this->end(self::IS_NOTICE, $message);
    }

    /**
     * Ends the scope as valid but with a warning
     *
     * @param string $message
     */
    public function warning($message)
    {
        $this->end(self::IS_WARNING, $message);
    }

    /**
     * Ends the scope as invalid
     *
     * @param string $message
     */
    public function invalid($message)
    {
        $this->end(self::IS_INVALID, $message);
    }

    /**
     * End Scope
     *
     * @param int $validity
     *   IS_[VALID|NOTICE|WARNING|INVALID]
     *
     * @param string $message
     *   If appropriate add a message to describe the problem.
     *
     * @return void
     */
    private function end($validity, $message)
    {
        $this->currentScope->setMessage($message);
        $this->currentScope->setValidity($validity);

        if (($parent = $this->currentScope->getParent()) !== null)
            $this->currentScope = $parent;

        if ($validity > $this->validity)
            $this->validity = $validity;
    }

    /**
     * Is the instance valid against the schema?
     *
     * @return bool
     */
    public function isValid()
    {
        return ($this->validity < self::IS_INVALID);
    }

    /**
     * Were any notices encountered?
     *
     * @return bool
     */
    public function hasNotice()
    {
        return ($this->validity == self::IS_NOTICE);
    }

    /**
     * Were any warnings encountered?
     *
     * @return bool
     */
    public function hasWarning()
    {
        return ($this->validity == self::IS_WARNING);
    }
}