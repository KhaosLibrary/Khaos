<?php

namespace Khaos\Bench\Tool\Twig;

use Khaos\Bench\Bench;
use Twig_Environment;
use Twig_Loader_Filesystem;

class TwigToolOperationProxy
{
    /**
     * @var Bench
     */
    private $bench;

    /**
     * @var Twig_Loader_Filesystem
     */
    private $loader;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * TwigToolOperationProxy constructor.
     *
     * @param Bench $bench
     */
    public function __construct(Bench $bench)
    {
        $this->bench = $bench;

        $options = [
            'cache' => BENCH_WORKING_DIRECTORY.'/.bench/cache'
        ];

        $loader = new Twig_Loader_Filesystem();
        $twig   = new Twig_Environment($loader, $options);

        $this->loader = $loader;
        $this->twig   = $twig;
    }

    public function render($template, $values = [])
    {
        if ($template[0] == '/') {
            $this->loader->setPaths(BENCH_WORKING_DIRECTORY);
            $template = substr($template, 1);
        } else {
            $this->loader->setPaths($this->bench->getContext()->getWorkingDirectory());
        }


        $test = $this->twig->render($template, $values);

        return $test;
    }
}