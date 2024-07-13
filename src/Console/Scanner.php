<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Console;

use Symblaze\MareScan\Analyzer\AnalyzerInterface;
use Symblaze\MareScan\Analyzer\Issue;
use Symfony\Component\Console\Helper\ProgressBar;

final class Scanner
{
    /** @return array<int, Issue[]> */
    public function scan(Config $config, ProgressBar $progressBar): array
    {
        $files = $config->getFinder()->getIterator();
        $analyzer = $config->getAnalyzer();
        $result = [];

        $progressBar->start();
        foreach ($files as $file) {
            $result[] = $this->scanFile($file, $analyzer, $progressBar);
        }

        $progressBar->finish();

        return $result;
    }

    private function scanFile(
        mixed $file,
        AnalyzerInterface $analyzer,
        ProgressBar $progressBar
    ): array {
        $result = $analyzer->analyze(['path' => $file->getPathname()]);
        $progressBar->advance();

        return $result;
    }
}
