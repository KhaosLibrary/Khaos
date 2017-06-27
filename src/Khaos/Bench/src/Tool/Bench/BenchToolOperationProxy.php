<?php

namespace Khaos\Bench\Tool\Bench;

use Khaos\Bench\Bench;
use Khaos\Bench\Tool\Bench\Operation\Help\ContextualHelpBuilder;
use Khaos\Console\Usage\Input;

class BenchToolOperationProxy
{
    /**
     * @var Bench
     */
    private $bench;

    /**
     * BenchToolOperationProxy constructor.
     *
     * @param Bench $bench
     */
    public function __construct(Bench $bench)
    {
        $this->bench = $bench;
    }

    /**
     * @param Input $input
     */
    public function help(Input $input)
    {
        (new ContextualHelpBuilder($this->bench))->build($input);
    }

    public function file($file)
    {
        if ($file[0] == '/') {
            $file = BENCH_WORKING_DIRECTORY . substr($file, 1);
        } else {
            $file = $this->bench->getContext()->getWorkingDirectory().'/'.$file;
        }

        return file_get_contents($file);
    }

    public function get($id)
    {
        return $this->bench->getDefinitionRepository()->{$id};
    }

    public function query($match)
    {
        return $this->bench->getDefinitionRepository()->query($match);
    }

    /**
     *
     */
    public function version()
    {
        echo '1.0';
    }
}