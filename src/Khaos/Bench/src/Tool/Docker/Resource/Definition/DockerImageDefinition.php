<?php

namespace Khaos\Bench\Tool\Docker\Resource\Definition;

use InvalidArgumentException;
use Khaos\Bench\Resource\BaseResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinition;

class DockerImageDefinition extends BaseResourceDefinition implements ResourceDefinition
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
