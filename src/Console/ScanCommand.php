<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Console;

use Symblaze\Console\Command;
use Symblaze\MareScan\Analyzer\Issue;
use Symblaze\MareScan\Foundation\Config;
use Symblaze\MareScan\Foundation\ConfigFinder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor - The properties are set in the run method.
 */
#[AsCommand(name: 'scan {--config=}', description: 'Scan the project for potential issues')]
final class ScanCommand extends Command
{
    public function __construct(private readonly ConfigFinder $configFinder)
    {
        parent::__construct();
    }

    public static function create(): self
    {
        return new self(new ConfigFinder());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Find Configuration
        $config = $this->findConfig();
        if (is_null($config)) {
            $this->output->error('No configuration file found');

            return self::FAILURE;
        }

        // Run the scan
        $this->output->info('Mare scan is running...');
        $this->output->newLine();
        $result = $this->scanFiles($config);

        // Display results
        if (empty($result[0] ?? [])) {
            $this->output->success('No issues found');

            return self::SUCCESS;
        }
        $this->displayIssues($result);

        return self::FAILURE;
    }

    /**
     * @return array<int, Issue[]>
     */
    private function scanFiles(Config $config): array
    {
        $files = $config->getFinder()->getIterator();
        $analyzer = $config->getAnalyzer();
        $result = [];

        foreach ($files as $file) {
            $path = $file->getPathname();
            $result[] = $analyzer->analyze(compact('path'));
        }

        return $result;
    }

    private function findConfig(): ?Config
    {
        $configPath = $this->option('config') ?? '';
        assert(is_string($configPath), 'Config path must be a string');

        return $this->configFinder->find($configPath);
    }

    /**
     * @param array<int, Issue[]> $result
     */
    private function displayIssues(array $result): void
    {
        $issuesCount = 0;
        foreach ($result as $issues) {
            foreach ($issues as $issue) {
                ++$issuesCount;
                $this->displayIssue($issue);
            }
        }

        $this->output->error("Total issues found: $issuesCount");
    }

    private function displayIssue(Issue $issue): void
    {
        $title = $issue->severity().': '.$issue->message();
        $this->output->warning($title);

        $at = 'at: '.$issue->file();
        $this->output->info($at);

        $this->output->newLine();
    }
}
