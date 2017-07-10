<?php

namespace Khaos\Bench2\Tool;

use Khaos\Bench2\Bench;

interface ToolPackage
{
    public function setBench(Bench $bench);
    public function getSchemaProvider();
    public function getSubscriber();
    public function getDependencies();
    public function getName();
    public function getCommandProxy();
}