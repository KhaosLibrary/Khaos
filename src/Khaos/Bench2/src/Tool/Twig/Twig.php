<?php

namespace Khaos\Bench2\Tool\Twig;

use SplFileInfo;
use Twig_Environment;
use Twig_Loader_Filesystem;

class Twig
{
    public function __construct()
    {
        $loader = new Twig_Loader_Filesystem();
        $twig   = new Twig_Environment($loader);

        $this->loader = $loader;
        $this->twig   = $twig;
    }

    public function render($template, $values = [])
    {
        $file = new SplFileInfo($template);
        $this->loader->setPaths($file->getPath());

        return $this->twig->render($file->getFilename(), $values);
    }
}