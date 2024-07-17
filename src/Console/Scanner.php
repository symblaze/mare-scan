<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Console;

use Iterator;
use SplFileInfo;
use Symblaze\MareScan\Inspector\CodeIssue;
use Symblaze\MareScan\Inspector\InspectorInterface;
use Symblaze\MareScan\Parser\ParserInterface;

final readonly class Scanner
{
    /**
     * @param Iterator<SplFileInfo> $files
     * @param InspectorInterface[]  $inspectors
     *
     * @return array<string, CodeIssue[]>
     */
    public function scan(
        Iterator $files,
        array $inspectors,
        ParserInterface $parser,
        ?callable $eachFileCallback = null
    ): array {
        $result = [];

        foreach ($files as $fileInfo) {
            $result[$fileInfo->getPathname()] = $this->inspectFile(
                $fileInfo,
                $inspectors,
                $parser,
                $eachFileCallback
            );
        }

        return $result;
    }

    /**
     * @param array<InspectorInterface> $inspectors
     *
     * @return array<CodeIssue>
     */
    private function inspectFile(
        SplFileInfo $file,
        array $inspectors,
        ParserInterface $parser,
        ?callable $callback = null
    ): array {
        $result = [];

        try {
            $statements = $parser->parse($file);
        } catch (CodeIssue $syntaxError) {
            return [$syntaxError];
        }

        foreach ($inspectors as $inspector) {
            $result = $inspector->inspect($file, ...$statements);
        }

        if (null !== $callback) {
            $callback($file, $result);
        }

        return $result;
    }
}
