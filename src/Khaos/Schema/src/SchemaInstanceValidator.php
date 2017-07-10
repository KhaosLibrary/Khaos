<?php

namespace Khaos\Schema;

use Exception;
use Khaos\Schema\Keywords\DescriptionKeyword;
use Khaos\Schema\Keywords\PropertiesKeyword;
use Khaos\Schema\Keywords\TypeKeyword;

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
    public function __construct(KeywordCollection $keywords = null)
    {
        if ($keywords == null)
        {
            $keywords = new KeywordCollection();
            $keywords->add(new TypeKeyword());
            $keywords->add(new PropertiesKeyword());
            $keywords->add(new DescriptionKeyword());
        }

        $this->keywords = $keywords;
    }

    public function addKeyword(Keyword $keyword)
    {
        $this->keywords->add($keyword);
    }

    public function hasKeyword($keyword)
    {
        return $this->keywords->has($keyword);
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
        // Coerce Types

        if (is_array($instance) && !isset($instance[0]))
            $instance = (object)$instance;

        // Validate Keywords

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