<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Parser;

use PhpParser\Error;
use SplFileInfo;
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

    public function parse(SplFileInfo $fileInfo): array
    {
        $code = file_get_contents($fileInfo->getRealPath());
        assert(is_string($code));

        try {
            return (array)$this->parser->parse($code);
        } catch (Error $e) {
            throw CodeIssue::fromParserError($e, $fileInfo, $code);
        }
    }
}
