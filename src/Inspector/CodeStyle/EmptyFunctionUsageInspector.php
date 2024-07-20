<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Inspector\CodeStyle;

use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Stmt;
use PhpParser\NodeFinder;
use SplFileInfo;
use Symblaze\MareScan\Inspector\CodeIssue;
use Symblaze\MareScan\Inspector\CodeLocation;
use Symblaze\MareScan\Inspector\InspectorInterface;

final class EmptyFunctionUsageInspector implements InspectorInterface
{
    private const string MESSAGE = 'Usage of `empty()` function is discouraged.';
    private const string SHORT_MESSAGE = 'The `empty()` function may lead to unexpected results. Use type-safe strict comparison instead.';

    public function shortName(): string
    {
        return 'empty_function_usage';
    }

    public function displayName(): string
    {
        return 'Empty function usage';
    }

    public function description(): string
    {
        return 'Detects the usage of `empty()` function.';
    }

    public function inspect(SplFileInfo $file, Stmt ...$statement): array
    {
        $result = [];
        $nodeFinder = new NodeFinder();

        $emptyFunctionNodes = $nodeFinder->findInstanceOf($statement, Empty_::class);
        foreach ($emptyFunctionNodes as $emptyFunctionNode) {
            $result[] = new CodeIssue(
                message: self::MESSAGE,
                severity: CodeIssue::SEVERITY_WARNING,
                codeLocation: CodeLocation::fromNode($file, $emptyFunctionNode),
                type: 'empty_function_usage',
                shortMessage: self::SHORT_MESSAGE,
            );
        }

        return $result;
    }
}
