<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Console;

interface IOInterface
{
    public function getOption(string $name): mixed;

    public function workDirectory(): string;
}
