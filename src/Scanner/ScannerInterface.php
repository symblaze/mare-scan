<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Scanner;

use SplFileInfo;
use Symblaze\MareScan\Inspector\CodeIssue;
use Symblaze\MareScan\Inspector\InspectorInterface;

interface ScannerInterface
{
    /**
     * @param InspectorInterface[] $inspectors
     *
     * @return CodeIssue[]|array<string, CodeIssue[]>
     */
    public function scan(array $inspectors, SplFileInfo $file): array;
}
