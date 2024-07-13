<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Console;

use Symblaze\MareScan\Exception\ConfigNotFoundException;

class ConfigFinder
{
    /**
     * @throws ConfigNotFoundException If the config file is not found
     */
    public function find(IOInterface $input): Config
    {
        $configOption = (string)$input->getOption('config');
        $configPath = empty($configOption) ? $input->workDirectory().'/.mare_scan.php' : $configOption;

        if (! file_exists($configPath)) {
            throw ConfigNotFoundException::create($configPath);
        }

        $config = require $configPath;
        assert($config instanceof Config);

        return $config;
    }
}
