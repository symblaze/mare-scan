<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Console;

use Symblaze\Console\Command;
use Symblaze\MareScan\Analyzer\FileAnalyzer;
use Symblaze\MareScan\Analyzer\Issue;
use Symblaze\MareScan\Exception\ConfigNotFoundException;
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
            $result = $this->scanFiles($config);

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

    /**
     * @return array<string, Issue[]>
     */
    private function scanFiles(Config $config): array
    {
        $this->comment('MareScan is scanning your project...');
        $this->newLine();

        $progressBar = $this->createProgressBar();
        assert($progressBar instanceof ProgressBar);
        $analyzer = new FileAnalyzer($config->getParser());

        return (new Scanner($analyzer, $progressBar))->scan($config->getFinder()->getIterator());
    }

    private function exitSuccess(): int
    {
        $this->newLine(2);
        $this->success('No issues found!');

        return self::SUCCESS;
    }

    /**
     * @param array<string, Issue[]> $result
     */
    private function exitError(array $result): int
    {
        $this->newLine(2);
        $this->error(sprintf('Found %d issue(s):', count($result)));
        $this->newLine();

        $this->displayIssues($result);

        return self::FAILURE;
    }

    /**
     * @param array<string, Issue[]> $result
     */
    private function displayIssues(array $result): void
    {
        foreach ($result as $issues) {
            foreach ($issues as $issue) {
                $this->displayIssue($issue);
            }

            $this->output->newLine();
        }
    }

    private function displayIssue(Issue $issue): void
    {
        $displayMethod = 'error' === $issue->severity() ? 'error' : 'warning';
        $title = $issue->severity().': '.$issue->message();
        $this->output->$displayMethod($title);

        $at = 'at: '.$issue->file();
        $this->output->info($at);
    }
}
