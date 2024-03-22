<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Parser;

use PhpParser\Error;
use Symblaze\MareScan\Exception\ParserError;

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
        try {
            return (array)$this->parser->parse($code);
        } catch (Error $e) {
            throw new ParserError($e->getMessage(), $e->getCode(), $e);
        }
    }
}
