<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Analyzer;

interface AnalyzerInterface
{
    /**
     * @param array $data
     *
     * @return Issue[]
     */
    public function analyze(array $data): array;
}
