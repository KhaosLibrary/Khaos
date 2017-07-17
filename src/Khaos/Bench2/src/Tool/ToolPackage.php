<?php

namespace Khaos\Bench2\Tool;

use Khaos\Bench2\BenchApplication;

interface ToolPackage
{
    public function getSchemaProvider(BenchApplication $bench);
    public function getSubscriber(BenchApplication $bench);
    public function getTool(BenchApplication $bench);
    public function getDependencies();
    public function getName();
}