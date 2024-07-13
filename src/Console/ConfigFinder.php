<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Console;

use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;

class ConfigFinder
{
    public function find(InputInterface $input): Config
    {
        $path = $input->getOption('config');
        assert(is_null($path) || is_string($path));

        if (is_null($path)) {
            // $path = getcwd().'/mare-scan.php';
            throw new RuntimeException('Config file not found');
        }

        if (file_exists($path)) {
            $config = require $path;

            if (! ($config instanceof Config)) {
                throw new RuntimeException('Invalid config file');
            }

            return $config;
        }

        throw new RuntimeException('Config file not found');
    }
}
