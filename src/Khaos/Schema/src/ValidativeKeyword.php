<?php

namespace Khaos\Schema;

interface ValidativeKeyword extends Keyword
{
    public function validate(&$schema, &$instance);
}