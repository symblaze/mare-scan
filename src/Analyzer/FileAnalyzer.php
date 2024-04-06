<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Analyzer;

use Symblaze\MareScan\Exception\InspectionError;
use Symblaze\MareScan\Exception\ParserError;
use Symblaze\MareScan\Inspector\InspectorInterface;
use Symblaze\MareScan\Inspector\TypeCompatibility\MissingDeclareStrictTypesInspector;
use Symblaze\MareScan\Parser\ParserInterface;

final readonly class FileAnalyzer implements AnalyzerInterface
{
    public function __construct(private ParserInterface $parser)
    {
    }

    public function analyze(array $data): array
    {
        $filePath = $data['path'] ?? null;

        if (! is_string($filePath) || ! file_exists($filePath)) {
            return [];
        }

        $content = (string)file_get_contents($filePath);
        try {
            $statements = $this->parser->parse($content);
        } catch (ParserError $e) {
            return [$this->parserErrorToResult($e, $filePath)];
        }

        $firstStatement = $statements[0] ?? null;
        $inspector = new MissingDeclareStrictTypesInspector($firstStatement);
        try {
            $inspector->inspect();
        } catch (InspectionError $e) {
            return [$this->inspectionErrorToResult($e, $filePath, $inspector)];
        }

        return [];
    }

    private function parserErrorToResult(ParserError $e, string $filePath): Issue
    {
        return Issue::fromArray([
            'severity' => 'error',
            'message' => $e->getMessage(),
            'file' => $filePath,
            'description' => 'Parser error occurred while parsing the file',
        ]);
    }

    private function inspectionErrorToResult(InspectionError $e, string $filePath, InspectorInterface $inspector): Issue
    {
        return Issue::fromArray([
            'severity' => 'warning',
            'message' => $e->getMessage(),
            'file' => $filePath,
            'description' => $inspector->description(),
        ]);
    }
}
