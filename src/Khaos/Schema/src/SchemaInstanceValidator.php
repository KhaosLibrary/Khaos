<?php

namespace Khaos\Schema;

use Exception;

class SchemaInstanceValidator
{
    /**
     * @var KeywordCollection
     */
    private $keywords;

    /**
     * SchemaInstanceValidator constructor.
     *
     * @param KeywordCollection $keywords
     */
    public function __construct(KeywordCollection $keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * @param array $schema
     * @param mixed $instance
     *
     * @return bool
     *
     * @throws Exception
     */
    public function validate($schema, &$instance)
    {
        $schema['type'] = $schema['type'] ?? '_dynamic';

        foreach (array_keys($schema) as $keyword)
            if (!$this->keywords->{$keyword}->validate($schema, $instance))
                return false;

        // Validate Object Properties

        if (isset($schema['type']) && $schema['type'] == 'object')
        {
            foreach ($instance as $property => &$value)
            {
                if (!$this->validate($schema['properties'][$property] ?? [], $value))
                    return false;
            }
        }

        // Validate Array Elements

        if (isset($schema['type']) && $schema['type'] == 'array')
        {
            foreach ($instance as &$value)
            {
                if (!$this->validate([], $value))
                    return false;
            }
        }

        return true;
    }
}