<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Inspector;

use PhpParser\Error;
use PhpParser\Node;
use RuntimeException;
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

    public static function fromNode(SplFileInfo $fileInfo, Node $node): self
    {
        $code = (string)file_get_contents($fileInfo->getRealPath());

        return new self(
            filePath: format_dir_separator((string)$fileInfo->getRealPath()),
            fileName: $fileInfo->getBasename(),
            lineNumber: $node->getStartLine(),
            columnNumber: self::getStartColumn($code, $node->getStartFilePos()),
            endLineNumber: $node->getEndLine(),
            endColumnNumber: self::getEndColumn($code, $node->getEndFilePos()),
        );
    }

    private static function getEndColumn(string $code, int $endFilePos): int
    {
        return self::toColumn($code, $endFilePos);
    }

    private static function getStartColumn(string $code, int $startFilePos): int
    {
        return self::toColumn($code, $startFilePos);
    }

    private static function toColumn(string $code, int $pos): int
    {
        if ($pos > strlen($code)) {
            throw new RuntimeException('Invalid position information');
        }

        $lineStartPos = strrpos($code, "\n", $pos - strlen($code));
        if (false === $lineStartPos) {
            $lineStartPos = -1;
        }

        return $pos - $lineStartPos;
    }
}
