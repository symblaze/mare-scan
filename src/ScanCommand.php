<?php

declare(strict_types=1);

namespace Symblaze\MareScan;

use Symblaze\Console\Command;
use Symblaze\MareScan\Analyzer\AnalyzerInterface;
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
    public function __construct(
        private readonly AnalyzerInterface $analyzer,
        private readonly ConfigFinder $configFinder
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Find Configuration
        $config = $this->findConfig();
        if (is_null($config)) {
            $this->error('No configuration file found');

            return self::FAILURE;
        }

        // Run the scan
        $this->info('Mare scan is running...');
        $this->line('');
        $result = $this->scanFiles($config);

        // Display results
        if (empty($result)) {
            $this->success('No issues found');

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
        $result = [];

        foreach ($files as $file) {
            $path = $file->getPathname();
            $result[] = $this->analyzer->analyze(compact('path'));
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

                $title = $issue->severity().': '.$issue->message();
                $this->warning($title);

                $at = 'at: '.$issue->file();
                $this->info($at);

                $this->line('');
            }
        }

        $this->error("Total issues found: $issuesCount");
    }
}
