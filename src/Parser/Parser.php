<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Parser;

use PhpParser\Error;
use Symblaze\MareScan\Inspector\CodeIssue;

final readonly class Parser implements ParserInterface
{
    public function __construct(
        private string $targetVersion,
        private \PhpParser\Parser $parser
    ) {
    }

    public function targetVersion(): string
    {
        return $this->targetVersion;
    }

    public function parse(string $code): array
    {
        $filePath = $code;
        $code = (string)file_get_contents($filePath);

        try {
            return (array)$this->parser->parse($code);
        } catch (Error $e) {
            throw CodeIssue::fromParserError($e, $filePath, $code);
        }
    }
}
