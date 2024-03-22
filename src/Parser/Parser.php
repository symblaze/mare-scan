<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Parser;

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
}
