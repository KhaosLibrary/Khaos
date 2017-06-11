<?php

namespace Khaos\Bench\Resource\Definition;

use InvalidArgumentException;
use Khaos\Bench\Resource\ResourceDefinition;

class CommandNamespaceDefinition extends BaseResourceDefinition implements ResourceDefinition
{
    const TYPE = 'bench/command-namespace';

    public function __construct(array $data)
    {
        if (!isset($data['definition']['namespace']))
            throw new InvalidArgumentException('namespace is a required field for command namespace resources.');

        $data['metadata']['id'] = $data['metadata']['id'] ?? self::getUniqueId();

        parent::__construct($data);
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function getNamespace()
    {
        return $this->data['definition']['namespace'];
    }

    public static function getUniqueId()
    {
        static $count = 0;
        return '_internal/bench/command-namespace/'.$count++;
    }
}
