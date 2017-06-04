<?php

namespace Khaos\Bench\Resource\Definition;

use InvalidArgumentException;
use Khaos\Bench\Resource\BaseResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinition;

class ImportDefinition extends BaseResourceDefinition implements ResourceDefinition
{
    const TYPE = 'bench/import';

    public function __construct(array $data)
    {
        $data['metadata']['id'] = $data['metadata']['id'] ?? self::getUniqueId();

        parent::__construct($data);
    }

    public function getImportPatterns()
    {
        return $this->data['import'];
    }

    public function getType()
    {
        return self::TYPE;
    }

    public static function getUniqueId()
    {
        static $count = 0;
        return '_internal/bench/import/'.$count++;
    }
}