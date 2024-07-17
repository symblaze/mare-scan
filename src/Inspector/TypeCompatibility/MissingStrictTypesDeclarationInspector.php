<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Inspector\TypeCompatibility;

use PhpParser\Node\DeclareItem;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Declare_;
use SplFileInfo;
use Symblaze\MareScan\Inspector\CodeIssue;
use Symblaze\MareScan\Inspector\CodeLocation;
use Symblaze\MareScan\Inspector\InspectorInterface;

final readonly class MissingStrictTypesDeclarationInspector implements InspectorInterface
{
    private const string MESSAGE = 'Strict types declaration is missing.';
    private const string SHORT_MESSAGE = 'District type declaration helps to avoid unexpected type coercion and improve code quality.';

    public function shortName(): string
    {
        return 'missing_strict_types_declaration';
    }

    public function displayName(): string
    {
        return 'Missing strict types declaration';
    }

    public function description(): string
    {
        return 'Detects the missing declare(strict_types=1) directive in the file.';
    }

    public function inspect(SplFileInfo $file, Stmt ...$statement): array
    {
        if (! isset($statement[0])) {
            return [$this->codeIssue($file)];
        }

        $statement = $statement[0];
        if (! $statement instanceof Declare_) {
            return [$this->codeIssue($file)];
        }

        $declares = $statement->declares;
        if (! isset($declares[0]) || ! $declares[0] instanceof DeclareItem) {
            return [$this->codeIssue($file)];
        }

        $declaration = $declares[0];
        if ('strict_types' !== $declaration->key->name) {
            return [$this->codeIssue($file)];
        }

        if (! $declaration->value instanceof Int_) {
            return [$this->codeIssue($file)];
        }

        if (1 !== $declaration->value->value) {
            return [$this->codeIssue($file)];
        }

        return [];
    }

    private function codeIssue(SplFileInfo $fileInfo): CodeIssue
    {
        return new CodeIssue(
            message: self::MESSAGE,
            severity: CodeIssue::SEVERITY_WARNING,
            codeLocation: CodeLocation::atBeginningOfFile($fileInfo),
            type: $this->shortName(),
            shortMessage: self::SHORT_MESSAGE
        );
    }
}
