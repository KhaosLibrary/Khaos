<?php

namespace Khaos\Schema;

use IteratorAggregate;

interface DataProvider extends IteratorAggregate
{


    public function getName();

    public function getLastModified();
}