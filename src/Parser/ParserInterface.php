<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Parser;

interface ParserInterface
{
    public function targetVersion(): string;

    public function parse(string $code): array;
}
