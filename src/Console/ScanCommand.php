<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Console;

use Symblaze\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor - The properties are set in the run method.
 */
#[AsCommand(name: 'scan {--c|config=}', description: 'Scan the project for potential issues')]
final class ScanCommand extends Command
{
    public static function create(): self
    {
        return new self();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
       
    }
}
