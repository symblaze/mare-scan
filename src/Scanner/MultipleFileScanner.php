<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Scanner;

use SplFileInfo;
use Symblaze\MareScan\Inspector\CodeIssue;
use Symblaze\MareScan\Inspector\InspectorInterface;

final readonly class MultipleFileScanner implements ScannerInterface
{
    public function __construct(
        /** @var FileScanner */
        private ScannerInterface $fileScanner
    ) {
    }

    /**
     * @param InspectorInterface[] $inspectors
     *
     * @return array<string, CodeIssue[]>
     */
    public function scan(array $inspectors, SplFileInfo ...$file): array
    {
        $result = [];

        foreach ($file as $fileInfo) {
            $codeIssues = $this->fileScanner->scan($inspectors, $fileInfo);
            if (0 !== count($codeIssues)) {
                $result[$fileInfo->getPathname()] = $codeIssues;
            }
        }

        return $result;
    }
}
