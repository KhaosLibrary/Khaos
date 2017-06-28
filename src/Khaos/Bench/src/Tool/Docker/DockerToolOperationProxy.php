<?php

namespace Khaos\Bench\Tool\Docker;

use Khaos\Bench\Bench;
use Khaos\Bench\Tool\Console\ConsoleToolOperationProxy;
use Khaos\Bench\Tool\Docker\Resource\Image\DockerImageDefinition;
use Khaos\Bench\Tool\Docker\Resource\Registry\DockerRegistryDefinition;
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

    public function push(Input $input)
    {
        /**
         * @var DockerImageDefinition     $image
         * @var DockerRegistryDefinition  $registry
         */

        $image    = $this->bench->getDefinitionRepository()->{'docker/image:'.$input->getArgument('image')};
        $registry = $this->bench->getDefinitionRepository()->{'docker/registry:default'};

        $cmd   = [];
        $cmd[] = 'docker login -u '.escapeshellarg($registry->getUsername()).' -p '.escapeshellarg($registry->getPassword()).' '.$registry->getServer();
        $cmd[] = 'docker push '.$image->getName().':'.$image->getVersion();
        $cmd[] = 'docker logout';

        $this->console->write('');
        $this->console->write('<heading>Push Docker Image</heading>');
        $this->console->write('<green>Image:</green> '.$image->getName().':'.$image->getVersion());
        $this->console->write('<green>Registry:</green> default');
        $this->console->write('');
        $this->console->write(implode("\n", $cmd));
        $this->console->write('');
        $this->console->write('<heading>Uploading...</heading>');

        system(implode(' && ', $cmd));
    }

    public function start()
    {
        $cmd   = [];
        $cmd[] = 'cd '.BENCH_WORKING_DIRECTORY;
        $cmd[] = 'docker-compose up -d';

        $this->console->write('<heading>Starting...</heading>');
        $this->console->write('');
        $this->console->write(implode("\n", $cmd));
        $this->console->write('');

        system(implode(' && ', $cmd));
    }

    public function stop()
    {
        $cmd   = [];
        $cmd[] = 'cd '.BENCH_WORKING_DIRECTORY;
        $cmd[] = 'docker-compose stop';

        $this->console->write('<heading>Stopping...</heading>');
        $this->console->write('');
        $this->console->write(implode("\n", $cmd));
        $this->console->write('');

        system(implode(' && ', $cmd));
    }

    public function destroy()
    {
        $cmd   = [];
        $cmd[] = 'cd '.BENCH_WORKING_DIRECTORY;
        $cmd[] = 'docker-compose stop';
        $cmd[] = 'docker-compose rm -f';

        $this->console->write('<heading>Destroying...</heading>');
        $this->console->write('');
        $this->console->write(implode("\n", $cmd));
        $this->console->write('');

        system(implode(' && ', $cmd));
    }
}