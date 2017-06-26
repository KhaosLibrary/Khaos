<?php

namespace Khaos\Bench\Tool\Docker\Resource\Image;

use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Tool\Bench\Resource\Command\BenchCommandSchema;
use Khaos\Bench\Tool\Docker\Resource\Image\DockerImageSchema;
use Khaos\Bench\Resource\Type\Map\MapType;
use Khaos\Bench\Resource\Type\TypeRepository;

/**
 * Class BenchCommandDefinition
 *
 */
class DockerImageDefinition implements Definition
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
     * @param TypeRepository  $typeRepository   Used to match and return values
     */
    public function __construct(array $data, TypeRepository $typeRepository)
    {
        $this->_data = $data;
        $this->_type = $typeRepository;
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
    public function __get($key)
    {
        return
            $this->{$key} = $this->_type->{DockerImageSchema::SCHEMA['map'][$key]['type']}->value(
                DockerImageSchema::SCHEMA['map'][$key],
                $this->_data[$key],
                $this
            );
    }

    /**
     * @inheritdoc
     */
    public function match($match)
    {
        return $this->_type->{DockerImageSchema::SCHEMA['type']}->match(DockerImageSchema::SCHEMA, $match, $this);
    }

    /**
     * @return string
     */
    public function export()
    {
        return $this->_type->{DockerImageSchema::SCHEMA['type']}->export(DockerImageSchema::SCHEMA, $this->_data);
    }
}