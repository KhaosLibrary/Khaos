<?php

namespace Khaos\Schema;

use IteratorAggregate;

interface DataProvider extends IteratorAggregate
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function getLastModified();
}