<?php

namespace Khaos\Bench2\Tool\Console;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;

class Console
{
    /**
     * @var ConsoleOutput
     */
    private $output;

    /**
     * Console constructor.
     */
    public function __construct()
    {
        $outputFormatter = new OutputFormatter();
        $outputFormatter->setStyle('heading', new OutputFormatterStyle('yellow', 'default'));
        $outputFormatter->setStyle('green',   new OutputFormatterStyle('green', 'default'));
        $outputFormatter->setStyle('yellow',  new OutputFormatterStyle('yellow', 'default'));

        $consoleOutput = new ConsoleOutput(ConsoleOutput::VERBOSITY_NORMAL, null, $outputFormatter);

        return $this->output = $consoleOutput;
    }

    public function write($text)
    {
        $this->output->writeLn($text);
    }
}