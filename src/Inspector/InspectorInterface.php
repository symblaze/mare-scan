<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Inspector;

use PhpParser\Node\Stmt;
use SplFileInfo;

interface InspectorInterface
{
    /**
     * @return CodeIssue[]
     */
    public function inspect(SplFileInfo $file, Stmt $statement): array;

    public function name(): string;

    public function description(): string;
}
