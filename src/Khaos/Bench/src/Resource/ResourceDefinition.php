<?php

namespace Khaos\Bench\Resource;

interface ResourceDefinition
{
    public function getTitle();
    public function getDescription();
    public function getType();
    public function getId();
    public function getWorkingDirectory();
    public function setMetaData($key, $value);
}
