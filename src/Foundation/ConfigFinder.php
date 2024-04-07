<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Foundation;

use RuntimeException;

class ConfigFinder
{
    public function find(string $path = ''): ?Config
    {
        if (file_exists($path)) {
            $config = require $path;
            assert($config instanceof Config, 'Config must be an instance of '.Config::class);

            return $config;
        }

        throw new RuntimeException('Config file not found');
    }
}
