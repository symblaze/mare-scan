<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Reporter;

use Symblaze\MareScan\Inspector\CodeIssue;

interface ReporterInterface
{
    /**
     * @param array<string, CodeIssue[]> $result
     */
    public function report(array $result): void;
}
