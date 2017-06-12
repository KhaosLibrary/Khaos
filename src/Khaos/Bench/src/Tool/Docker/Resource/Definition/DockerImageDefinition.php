<?php

namespace Khaos\Bench\Tool\Docker\Resource\Definition;

use InvalidArgumentException;
use Khaos\Bench\Resource\Definition\AbstractResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinition;

class DockerImageDefinition extends AbstractResourceDefinition implements ResourceDefinition
{
    const TYPE = 'docker/image';

    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public function getType()
    {
        return self::TYPE;
    }
}
