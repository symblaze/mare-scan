<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Inspector;

use SplFileInfo;
use Symblaze\MareScan\Parser\ParserInterface;

interface InspectorInterface
{
    /**
     * @return CodeIssue[]
     */
    public function inspect(ParserInterface $parser, SplFileInfo $file): array;

    public function name(): string;

    public function description(): string;
}
