<?php

namespace Khaos\Schema;

interface Keyword
{
    public function getKeyword();
    public function validate(&$schema, &$instance);
}