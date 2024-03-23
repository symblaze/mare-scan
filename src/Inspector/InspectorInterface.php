<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Inspector;

interface InspectorInterface
{
    public function name(): string;

    public function description(): string;
}
