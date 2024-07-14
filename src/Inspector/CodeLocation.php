<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Inspector;

final readonly class CodeLocation
{
    public function __construct(
        public string $filePath,
        public string $fileName,
        public int $lineNumber,
        public int $columnNumber,
        public int $endLineNumber,
    ) {
    }
}
