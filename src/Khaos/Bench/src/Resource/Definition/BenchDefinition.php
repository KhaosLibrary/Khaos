<?php

namespace Khaos\Bench\Resource\Definition;

use Khaos\Bench\Resource\Definition\BaseResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinition;

class BenchDefinition extends BaseResourceDefinition implements ResourceDefinition
{
    const TYPE = 'bench';

    public function __construct(array $data)
    {
        $data['tools'] = $data['tools'] ?? [];

        parent::__construct($data);
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function getTools()
    {
        return $this->data['tools'];
    }
}