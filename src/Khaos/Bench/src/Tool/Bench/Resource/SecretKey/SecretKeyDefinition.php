<?php


namespace Khaos\Bench\Tool\Bench\Resource\SecretKey;

use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Type\TypeRepository;

class SecretKeyDefinition implements Definition
{
    /**
     * @var array
     */
    private $_data;

    /**
     * @var TypeRepository
     */
    private $_type;

    /**
     * DynamicDefinition constructor.
     *
     * @param array         $data           Data which will make up this definition
     * @param TypeRepository  $typeResolver   Used to match and return values
     */
    public function __construct(array $data, TypeRepository $typeResolver)
    {
        $this->_data = $data;
        $this->_type = $typeResolver;
    }

    /**
     * @inheritdoc
     */
    public function __get($key)
    {
        return
            $this->{$key} = $this->_type->{SecretKeySchema::SCHEMA['map'][$key]['type']}->value(
                SecretKeySchema::SCHEMA['map'][$key],
                $this->_data[$key],
                $this
            );
    }

    public function getKey()
    {
        return $this->{'definition'}->{'key'};
    }

    public function getWorkingDirectory()
    {
        return $this->{'metadata'}->{'working-directory'};
    }

    public function getSourceFile()
    {
        return $this->{'metadata'}->{'source-file'};
    }

    /**
     * @inheritdoc
     */
    public function match($match)
    {
        return $this->_type->{SecretKeySchema::SCHEMA['type']}->match(SecretKeySchema::SCHEMA, $match, $this);
    }

    /**
     * @return string
     */
    public function export()
    {
        return $this->_type->{SecretKeySchema::SCHEMA['type']}->export(SecretKeySchema::SCHEMA, $this->_data);
    }
}