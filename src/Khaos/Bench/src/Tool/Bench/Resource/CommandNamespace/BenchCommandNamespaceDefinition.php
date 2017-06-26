<?php


namespace Khaos\Bench\Tool\Bench\Resource\CommandNamespace;


use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Type\TypeRepository;

class BenchCommandNamespaceDefinition implements Definition
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
            $this->{$key} = $this->_type->{BenchCommandNamespaceSchema::SCHEMA['map'][$key]['type']}->value(
                BenchCommandNamespaceSchema::SCHEMA['map'][$key],
                $this->_data[$key],
                $this
            );
    }

    public function getNamespace()
    {
        return $this->{'definition'}->{'namespace'};
    }

    public function getTitle()
    {
        return $this->{'metadata'}->{'title'};
    }

    public function getDescription()
    {
        return $this->{'metadata'}->{'description'};
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
        return $this->_type->{BenchCommandNamespaceSchema::SCHEMA['type']}->match(BenchCommandNamespaceSchema::SCHEMA, $match, $this);
    }

    /**
     * @return string
     */
    public function export()
    {
        return $this->_type->{BenchCommandNamespaceSchema::SCHEMA['type']}->export(BenchCommandNamespaceSchema::SCHEMA, $this->_data);
    }
}