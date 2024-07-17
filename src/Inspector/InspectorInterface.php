<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Inspector;

use PhpParser\Node\Stmt;
use SplFileInfo;

interface InspectorInterface
{
    /**
     * Return the short name of the inspector.
     */
    public function shortName(): string;

    /**
     * Return the display name of the inspector.
     */
    public function displayName(): string;

    /**
     * Return the description of the inspector.
     */
    public function description(): string;

    /**
     * Inspect the given statement and return an array of CodeIssue.
     *
     * @return CodeIssue[]
     */
    public function inspect(SplFileInfo $file, Stmt $statement): array;
}
