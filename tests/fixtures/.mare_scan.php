<?php

declare(strict_types=1);

use Symblaze\MareScan\Foundation\Config;
use Symfony\Component\Finder\Finder;

$finder = new Finder();
$finder->in(__DIR__.'/scan_command');

$config = new Config();
$config->setFinder($finder);

return $config;
