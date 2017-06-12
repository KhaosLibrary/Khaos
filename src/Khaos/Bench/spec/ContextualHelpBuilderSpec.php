<?php

namespace spec\Khaos\Bench;

use ArrayIterator;
use Khaos\Bench\Tool\Bench\Functions\Help\ContextualHelpBuilder;
use Khaos\Bench\Tool\Bench\Resource\Definition\CommandDefinition;
use Khaos\Bench\Tool\Bench\Resource\Definition\NamespaceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Khaos\Console\Usage\Model\OptionDefinition;
use Khaos\Console\Usage\Model\OptionDefinitionRepository;
use PhpSpec\ObjectBehavior;

class ContextualHelpBuilderSpec extends ObjectBehavior
{
    function let(ResourceDefinitionRepository $definitions)
    {
        $this->beConstructedWith($definitions);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContextualHelpBuilder::class);
    }

    function it_provides_command_definition_usage(CommandDefinition $commandDefinition)
    {
        $commandDefinition->getCommand()->willReturn('help');
        $commandDefinition->getTitle()->willReturn('Global help message');

        $this->getCommandUsage($commandDefinition)->shouldBe(['help', 'Global help message']);
    }

    /**
     * TODO: split into distinct tests for each assertion
     */
    function it_provides_sub_namespaces
    (
        ResourceDefinitionRepository $definitions,
        NamespaceDefinition $namespace1,
        NamespaceDefinition $namespace2,
        NamespaceDefinition $namespace3,
        NamespaceDefinition $namespace4,
        NamespaceDefinition $namespace5
    ) {

        $namespace1->getNamespace()->willReturn('kubernetes');
        $namespace1->getTitle()->willReturn('Kubernetes related commands');

        $namespace2->getNamespace()->willReturn('application');
        $namespace2->getTitle()->willReturn('Application related commands');

        $namespace3->getNamespace()->willReturn('application deploy');
        $namespace3->getTitle()->willReturn('Deploy commands');

        $namespace4->getNamespace()->willReturn('application deploy cluster');
        $namespace4->getTitle()->willReturn('Cluster related commands');

        $namespace5->getNamespace()->willReturn('application backup');
        $namespace5->getTitle()->willReturn('backup related commands');

        $definitions->findByType(NamespaceDefinition::TYPE)->willReturn([
            $namespace1,
            $namespace2,
            $namespace3,
            $namespace4,
            $namespace5
        ]);

        $this->beConstructedWith($definitions);

        $this->getChildNamespaces(null)->shouldBe([
            'application' => 'Application related commands',
            'kubernetes'  => 'Kubernetes related commands'
        ]);

        $this->getChildNamespaces('kubernetes')->shouldBe([]);

        $this->getChildNamespaces('application')->shouldBe([
            'backup' => 'backup related commands',
            'deploy' => 'Deploy commands'
        ]);

        $this->getChildNamespaces('application deploy')->shouldBe([
            'cluster' => 'Cluster related commands'
        ]);

        $this->getChildNamespaces('application backup')->shouldBe([]);
    }

    /**
     * TODO: split into distinct tests for each assertion
     */
    public function it_provides_sub_commands(
        ResourceDefinitionRepository $definitions,
        CommandDefinition $command1,
        CommandDefinition $command2,
        CommandDefinition $command3
    ) {
        $command1->getNamespace()->willReturn(null);
        $command1->getCommand()->willReturn('deploy');
        $command1->getTitle()->willReturn('Deploy related command');

        $command2->getNamespace()->willReturn('docker');
        $command2->getCommand()->willReturn('push');
        $command2->getTitle()->willReturn('push images to registry');

        $command3->getNamespace()->willReturn('docker');
        $command3->getCommand()->willReturn('build');
        $command3->getTitle()->willReturn('build docker images');

        $definitions->findByType(CommandDefinition::TYPE)->willReturn([
            $command1,
            $command2,
            $command3
        ]);

        $this->getChildCommands(null)->shouldBe([
            'deploy' => 'Deploy related command'
        ]);

        $this->getChildCommands('docker')->shouldBe([
            'build' => 'build docker images',
            'push'  => 'push images to registry'
        ]);
    }

    public function it_provides_context_type_of_namespace_when_context_is_a_namespace(ResourceDefinitionRepository $definitions, NamespaceDefinition $namespace1)
    {
        $namespace1->getNamespace()->willReturn('docker');
        $namespace1->getTitle()->willReturn('docker related commands');

        $definitions->findByType(NamespaceDefinition::TYPE)->willReturn([$namespace1]);

        $this->getHelpContext('docker')->shouldBe($namespace1);
    }

    public function it_provides_context_type_of_command_when_context_is_a_command(ResourceDefinitionRepository $definitions, CommandDefinition $command1)
    {
        $command1->getNamespace()->willReturn(null);
        $command1->getCommand()->willReturn('deploy');
        $command1->getTitle()->willReturn('Deploy related command');

        $definitions->findByType(NamespaceDefinition::TYPE)->willReturn([]);
        $definitions->findByType(CommandDefinition::TYPE)->willReturn([$command1]);

        $this->getHelpContext('deploy')->shouldBe($command1);
    }

    public function it_provides_context_of_type_command_when_context_is_a_namespaced_command(ResourceDefinitionRepository $definitions, NamespaceDefinition $namespace1, CommandDefinition $command1)
    {
        $namespace1->getNamespace()->willReturn('docker');
        $namespace1->getTitle()->willReturn('docker related commands');

        $command1->getNamespace()->willReturn('docker');
        $command1->getCommand()->willReturn('build');
        $command1->getTitle()->willReturn('build docker images');

        $definitions->findByType(NamespaceDefinition::TYPE)->willReturn([$namespace1]);
        $definitions->findByType(CommandDefinition::TYPE)->willReturn([$command1]);

        $this->getHelpContext('docker build')->shouldBe($command1);
    }

    public function it_provides_context_of_type_null_when_context_cant_be_matched(ResourceDefinitionRepository $definitions)
    {
        $definitions->findByType(NamespaceDefinition::TYPE)->willReturn([]);
        $definitions->findByType(CommandDefinition::TYPE)->willReturn([]);

        $this->getHelpContext('docker build')->shouldBe(null);
    }
}
