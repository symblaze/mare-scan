<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Config;

use Symblaze\MareScan\Inspector\CodeStyle\EmptyFunctionUsageInspector;
use Symblaze\MareScan\Inspector\InspectorInterface;
use Symblaze\MareScan\Inspector\TypeCompatibility\MissingStrictTypesDeclarationInspector;

final class InspectorGenerator
{
    private const array INSPECTORS = [
        MissingStrictTypesDeclarationInspector::class,
        EmptyFunctionUsageInspector::class,
    ];

    /**
     * @return array<InspectorInterface>
     */
    public function all(): array
    {
        $inspectors = [];

        foreach (self::INSPECTORS as $inspector) {
            $inspectors[] = new $inspector();
        }

        return $inspectors;
    }
}
