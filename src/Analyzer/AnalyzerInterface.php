<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Analyzer;

interface AnalyzerInterface
{
    /**
     * @return Issue[]
     */
    public function analyze(array $data): array;
}
