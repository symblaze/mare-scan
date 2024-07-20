# Mare Scan

## Usage

You need to create MareScan configuration file named `.mare_scan.php` in the root of your project.

```php
// .mare_scan.php
<?php

declare(strict_types=1);

use Symblaze\MareScan\Config\Config;

$config = Config::create();

// Below is an example of how to configure MareScan
$config->in([__DIR__.'/src'])->exclude([__DIR__ . '/vendor'])->files();

return $config;
```

Then you can run MareScan using the following command:

```bash
vendor/bin/mare scan
```

## Available Inspectors

## Code Style

- Empty Function Usage `EmptyFunctionUsageInspector`

## Type Compatibility

- Missing Strict Types declaration `MissingStrictTypesDeclarationInspector`
