<?php

namespace Khaos\Bench\Tool\Bench;

use Khaos\Bench\Bench;
use Khaos\Bench\Tool\Bench\Operation\Help\ContextualHelpBuilder;
use Khaos\Bench\Tool\Bench\Resource\SecretKey\SecretKeyDefinition;
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

    public function version()
    {
        echo '1.0';
    }

    public function encrypt($data, $key = 'default')
    {
        /**
         * @var SecretKeyDefinition $key
         */

        $secretKeyDefinition = $this->bench->getDefinitionRepository()->{'secret/key:'.$key};
        return Cryptor::Encrypt($data, $secretKeyDefinition->getKey());
    }

    public function decrypt($data, $key = 'default')
    {
        /**
         * @var SecretKeyDefinition $secretKeyDefinition
         */

        $secretKeyDefinition = $this->bench->getDefinitionRepository()->{'secret/key:'.$key};
        $key                 = $secretKeyDefinition->getKey();
        $decryptedValue      = Cryptor::Decrypt($data, $key);

        return $decryptedValue;
    }
}