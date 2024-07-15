<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Console;

use Symblaze\Console\Command;
use Symblaze\MareScan\Config\Config;
use Symblaze\MareScan\Exception\ConfigNotFoundException;
use Symblaze\MareScan\Inspector\CodeIssue;
use Symblaze\MareScan\Reporter\Display;
use Symblaze\MareScan\Reporter\OutputInterface as ConsoleOutput;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor - The properties are set in the run method.
 */
#[AsCommand(name: 'scan {--c|config=}', description: 'Scan the project for potential issues')]
final class ScanCommand extends Command implements ConsoleOutput
{
    use ConsoleHelpers;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $config = $this->findConfiguration($input);

            $this->comment('MareScan is scanning your project...');
            $this->newLine();

            $progressBar = $this->createProgressBar($config->getFinder()->count());
            assert($progressBar instanceof ProgressBar);
            $progressBar->start();

            $result = (new Scanner())->scan(
                $config->getFinder()->getIterator(),
                $config->getInspectors(),
                $config->getParser(),
                function () use ($progressBar): void {
                    $progressBar->advance();
                }
            );

            $progressBar->finish();

            return empty($result) ? $this->exitSuccess() : $this->exitError($result);
        } catch (ConfigNotFoundException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }

    private function findConfiguration(InputInterface $input): Config
    {
        $configOption = (string)$input->getOption('config');
        $workDir = (string)getcwd();

        return (new ConfigFinder())->find($configOption, $workDir);
    }

    private function exitSuccess(): int
    {
        $this->newLine(2);
        $this->success('No issues found!');

        return self::SUCCESS;
    }

    /**
     * @param array<string, CodeIssue[]> $result
     */
    private function exitError(array $result): int
    {
        (new Display($this))->report($result);

        return self::FAILURE;
    }
}
