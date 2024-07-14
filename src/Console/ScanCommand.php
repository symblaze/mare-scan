<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Console;

use Symblaze\Console\Command;
use Symblaze\MareScan\Config\Config;
use Symblaze\MareScan\Exception\ConfigNotFoundException;
use Symblaze\MareScan\Inspector\CodeIssue;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor - The properties are set in the run method.
 */
#[AsCommand(name: 'scan {--c|config=}', description: 'Scan the project for potential issues')]
final class ScanCommand extends Command
{
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
        $this->newLine(2);
        $this->error(sprintf('Found %d issue(s):', count($result)));
        $this->newLine();

        $this->displayIssues($result);

        return self::FAILURE;
    }

    private function displayIssues(array $result): void
    {
        foreach ($result as $issues) {
            foreach ($issues as $issue) {
                $this->displayIssue($issue);
            }

            $this->output->newLine();
        }
    }

    private function displayIssue(CodeIssue $issue): void
    {
        // Title
        $displayMethod = 'error' === $issue->getSeverity() ? 'error' : 'warning';
        $title = strtoupper($issue->getSeverity()).': '.$issue->getMessage();
        $this->output->$displayMethod($title);

        // at:
        $at = sprintf(
            'at: %s:%s',
            $issue->getCodeLocation()->filePath,
            $issue->getCodeLocation()->lineNumber,
        );
        $this->output->info($at);

        // Short message
        $this->comment($issue->getShortMessage());
    }
}
