<?php

namespace Khaos\Bench\Resource\Definition;

use Khaos\Bench\Resource\Definition\BaseResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinition;

class BenchDefinition extends BaseResourceDefinition implements ResourceDefinition
{
    const TYPE = 'bench';

    public function __construct(array $data)
    {
        $data['metadata']['id'] = $data['metadata']['id'] ?? self::getUniqueId();

        $data['tools']   = $data['tools']   ?? [];
        $data['options'] = $data['options'] ?? [];

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

    public static function getUniqueId()
    {
        static $count = 0;
        return '_internal/bench/'.$count++;
    }
}