<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Inspector;

use PhpParser\Error;
use SplFileInfo;

final readonly class CodeLocation
{
    public function __construct(
        public string $filePath,
        public string $fileName,
        public int $lineNumber,
        public int $columnNumber,
        public int $endLineNumber,
        public int $endColumnNumber = -1,
    ) {
    }

    public static function fromParserError(SplFileInfo $fileInfo, Error $error, string $code): self
    {
        return new self(
            filePath: format_dir_separator((string)$fileInfo->getRealPath()),
            fileName: $fileInfo->getBasename(),
            lineNumber: $error->getStartLine(),
            columnNumber: $error->hasColumnInfo() ? $error->getStartColumn($code) : -1,
            endLineNumber: $error->getEndLine(),
            endColumnNumber: $error->hasColumnInfo() ? $error->getEndColumn($code) : -1,
        );
    }

    public static function atBeginningOfFile(SplFileInfo $fileInfo): self
    {
        return new self(
            filePath: format_dir_separator((string)$fileInfo->getRealPath()),
            fileName: $fileInfo->getBasename(),
            lineNumber: 2,
            columnNumber: 1,
            endLineNumber: 2,
            endColumnNumber: 1,
        );
    }
}
