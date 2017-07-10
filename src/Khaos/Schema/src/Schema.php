<?php

namespace Khaos\Schema;

interface Schema
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getSchema();

    /**
     * @return InstanceFactory
     */
    public function getInstanceFactory();
}