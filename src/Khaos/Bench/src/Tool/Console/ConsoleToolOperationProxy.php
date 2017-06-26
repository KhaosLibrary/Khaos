<?php

namespace Khaos\Bench\Tool\Console;

use Exception;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class ConsoleToolOperationProxy
 *
 * @property ConsoleOutput $symfonyConsoleOutput
 *
 * @package Khaos\Bench\Tool\Console
 */
class ConsoleToolOperationProxy
{
    /**
     * Write Output
     *
     * @param string $text
     */
    public function write($text)
    {
        $lines = explode("\n", $text);

        foreach ($lines as $line)
            $this->symfonyConsoleOutput->writeLn($line);
    }

    /**
     * Lazy Load
     *
     * @param string $key
     *
     * @return ConsoleOutput
     * @throws Exception
     */
    public function __get($key)
    {
        if ($key != 'symfonyConsoleOutput')
            throw new Exception();

        $outputFormatter = new OutputFormatter();
        $outputFormatter->setStyle('heading', new OutputFormatterStyle('yellow', 'default'));
        $outputFormatter->setStyle('green',   new OutputFormatterStyle('green', 'default'));
        $outputFormatter->setStyle('yellow',  new OutputFormatterStyle('yellow', 'default'));

        $consoleOutput = new ConsoleOutput(ConsoleOutput::VERBOSITY_NORMAL, null, $outputFormatter);

        return $this->symfonyConsoleOutput = $consoleOutput;
    }
}