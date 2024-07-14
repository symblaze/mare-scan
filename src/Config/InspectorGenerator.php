<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Config;

use Symblaze\MareScan\Inspector\InspectorInterface;
use Symblaze\MareScan\Inspector\TypeCompatibility\MissingDeclareStrictTypesInspector;

final class InspectorGenerator
{
    private const array INSPECTORS = [
        MissingDeclareStrictTypesInspector::class,
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
