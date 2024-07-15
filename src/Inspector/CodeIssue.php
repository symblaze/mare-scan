<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Inspector;

use PhpParser\Error;
use Symblaze\MareScan\Exception\MareScanException;

final class CodeIssue extends MareScanException
{
    public const int SEVERITY_ERROR = 1;
    public const int SEVERITY_WARNING = 2;

    public function __construct(
        string $message,
        int $severity,
        private readonly CodeLocation $codeLocation,
        private readonly string $type,
        private readonly string $shortMessage,
    ) {
        parent::__construct($message, $severity);
    }

    public static function fromParserError(Error $error, string $filePath, string $code): self
    {
        $formattedPath = self::unifyDirectorySeparator($filePath);

        $codeLocation = new CodeLocation(
            filePath: $formattedPath,
            fileName: basename($formattedPath),
            lineNumber: $error->getStartLine(),
            columnNumber: $error->getStartColumn($code),
            endLineNumber: $error->getEndLine(),
        );

        return new self(
            message: $error->getMessage(),
            severity: self::SEVERITY_ERROR,
            codeLocation: $codeLocation,
            type: 'SyntaxError',
            shortMessage: $error->getMessageWithColumnInfo($code)
        );
    }

    public function getCodeLocation(): CodeLocation
    {
        return $this->codeLocation;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getShortMessage(): string
    {
        return $this->shortMessage;
    }

    public function getSeverity(): string
    {
        return match ($this->code) {
            self::SEVERITY_ERROR => 'error',
            self::SEVERITY_WARNING => 'warning',
            default => 'unknown',
        };
    }

    private static function unifyDirectorySeparator(string $path): string
    {
        return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
    }
}
