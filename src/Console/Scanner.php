<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Console;

use Iterator;
use SplFileInfo;
use Symblaze\MareScan\Analyzer\AnalyzerInterface;
use Symblaze\MareScan\Analyzer\Issue;
use Symfony\Component\Console\Helper\ProgressBar;

final readonly class Scanner
{
    public function __construct(
        private AnalyzerInterface $analyzer,
        private ProgressBar $progressBar
    ) {
    }

    /**
     * @param Iterator<SplFileInfo> $files
     *
     * @return array<string, Issue[]>
     */
    public function scan(Iterator $files): array
    {
        $this->setUpProgressBar(iterator_count($files));

        $result = [];
        $this->progressBar->start();
        foreach ($files as $file) {
            $issues = $this->scanFile($file);
            if (empty($issues)) {
                continue;
            }
            $result[$file->getPathname()] = $this->scanFile($file);
        }

        $this->progressBar->finish();

        return $result;
    }

    /**
     * @return Issue[]
     */
    private function scanFile(SplFileInfo $file): array
    {
        $result = $this->analyzer->analyze(['path' => $file->getPathname()]);
        $this->progressBar->advance();

        return $result;
    }

    private function setUpProgressBar(int $max): void
    {
        $this->progressBar->setMaxSteps($max);
        $this->progressBar->setFormat(' %bar% ');
    }
}
