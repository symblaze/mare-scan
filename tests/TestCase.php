<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * All unit tests should extend this class.
 */
abstract class TestCase extends PHPUnitTestCase
{
    protected function fixture(string $path): string
    {
        $contents = file_get_contents(__DIR__.'/fixtures/'.$path);
        assert(is_string($contents));

        return $contents;
    }
}
