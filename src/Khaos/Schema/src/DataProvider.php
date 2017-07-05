<?php

namespace Khaos\Schema;

use Traversable;

interface DataProvider extends Traversable
{
    public function get($id);
}