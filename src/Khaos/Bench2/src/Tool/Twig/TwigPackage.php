<?php

namespace Khaos\Bench2\Tool\Twig;

use Khaos\Bench2\BenchApplication;
use Khaos\Bench2\Tool\ToolPackage;

class TwigPackage implements ToolPackage
{
    const NAME = 'twig';

    /**
     * @inheritdoc
     */
    public function getSchemaProvider(BenchApplication $bench)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getSubscriber(BenchApplication $bench)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getTool(BenchApplication $bench)
    {
        return new Twig();
    }

    /**
     * @inheritdoc
     */
    public function getDependencies()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }
}