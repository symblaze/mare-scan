<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Parser;

use PhpParser\Node\Stmt;

interface ParserInterface
{
    public function targetVersion(): string;

    /**
     * @return Stmt[]
     */
    public function parse(string $code): array;
}
