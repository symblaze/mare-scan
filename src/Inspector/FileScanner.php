<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Inspector;

use Closure;
use SplFileInfo;
use Symblaze\MareScan\Parser\ParserInterface;

final readonly class FileScanner implements ScannerInterface
{
    public function __construct(
        private ParserInterface $parser,
        private ?Closure $callback = null
    ) {
    }

    /**
     * @param InspectorInterface[] $inspectors
     *
     * @return CodeIssue[]
     */
    public function scan(array $inspectors, SplFileInfo $file): array
    {
        $result = [];

        try {
            $statements = $this->parser->parse($file);
        } catch (CodeIssue $syntaxError) {
            return [$syntaxError];
        }

        foreach ($inspectors as $inspector) {
            $result = $inspector->inspect($file, ...$statements);
        }

        $this->callback?->call($this, $file, $result);

        return $result;
    }
}
