<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Parser;

use PhpParser\Node\Stmt;
use SplFileInfo;

interface ParserInterface
{
    /**
     * @return Stmt[]
     */
    public function parse(SplFileInfo $fileInfo): array;
}
