<?php

namespace Khaos\Bench\Tool\Docker;

use Khaos\Bench\Bench;
use Khaos\Bench\Tool\Console\ConsoleToolOperationProxy;
use Khaos\Bench\Tool\Docker\Resource\Image\DockerImageDefinition;
use Khaos\Console\Usage\Input;

class DockerToolOperationProxy
{
    /**
     * @var ConsoleToolOperationProxy
     */
    private $console;

    /**
     * @var Bench
     */
    private $bench;

    /**
     * DockerToolOperationProxy constructor.
     *
     * @param Bench $bench
     */
    public function __construct(Bench $bench)
    {
        $this->bench   = $bench;
        $this->console = $bench->tool('console');
    }


    public function build(Input $input)
    {
        /**
         * @var DockerImageDefinition $image
         */

        $image   = $this->bench->getDefinitionRepository()->{'docker/image:'.$input->getArgument('image')};
        $command = 'docker build --no-cache --pull --rm -t '.$image->getName().':'.$image->getVersion().' -f '.$image->getDockerfile().' '.$image->getContext();

        $this->console->write('');
        $this->console->write('<heading>Build Docker Image</heading>');
        $this->console->write('<green>Image:</green> '.$image->getName().':'.$image->getVersion());
        $this->console->write('');
        $this->console->write($command);
        $this->console->write('');
        $this->console->write('<heading>Building...</heading>');

        system($command);
    }
}