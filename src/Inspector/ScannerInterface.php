<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Inspector;

use SplFileInfo;

interface ScannerInterface
{
    /**
     * @param InspectorInterface[] $inspectors
     *
     * @return CodeIssue[]|array<string, CodeIssue[]>
     */
    public function scan(array $inspectors, SplFileInfo $file): array;
}
