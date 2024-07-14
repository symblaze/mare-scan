<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Inspector\TypeCompatibility;

use PhpParser\Node\DeclareItem;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Stmt\Declare_;
use SplFileInfo;
use Symblaze\MareScan\Inspector\CodeIssue;
use Symblaze\MareScan\Inspector\CodeLocation;
use Symblaze\MareScan\Inspector\InspectorInterface;
use Symblaze\MareScan\Parser\ParserInterface;

final readonly class MissingDeclareStrictTypesInspector implements InspectorInterface
{
    private const string MESSAGE = 'Strict types declaration is missing';
    private const string SHORT_MESSAGE = 'District type declaration helps to avoid unexpected type coercion and improve code quality.';
    private const string ISSUE_TYPE = 'MissingDeclareStrictTypes';

    public function __construct()
    {
    }

    public function name(): string
    {
        return 'Missing declare strict types';
    }

    public function description(): string
    {
        return 'Detects the missing declare(strict_types=1) directive in the file.';
    }

    public function inspect(ParserInterface $parser, SplFileInfo $file): array
    {
        try {
            $stmts = $parser->parse($file->getRealPath());
        } catch (CodeIssue $syntaxError) {
            return [$syntaxError];
        }

        if (! isset($stmts[0])) {
            return [];
        }

        $statement = $stmts[0];
        $codeLocation = new CodeLocation(
            filePath: $file->getRealPath(),
            fileName: $file->getFilename(),
            lineNumber: $statement->getStartLine(),
            columnNumber: 1,
            endLineNumber: $statement->getEndLine(),
        );
        $codeIssue = new CodeIssue(
            message: self::MESSAGE,
            severity: CodeIssue::SEVERITY_WARNING,
            codeLocation: $codeLocation,
            type: self::ISSUE_TYPE,
            shortMessage: self::SHORT_MESSAGE
        );

        if (! $statement instanceof Declare_) {
            return [$codeIssue];
        }

        $declares = $statement->declares;
        if (! isset($declares[0]) || ! $declares[0] instanceof DeclareItem) {
            return [$codeIssue];
        }

        $declaration = $declares[0];
        if ('strict_types' !== $declaration->key->name) {
            return [$codeIssue];
        }

        if (! $declaration->value instanceof Int_) {
            return [$codeIssue];
        }

        if (1 !== $declaration->value->value) {
            return [$codeIssue];
        }

        return [];
    }
}
