<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests;

use Symblaze\MareScan\Example;

/**
 * @covers \Symblaze\MareScan\Example
 */
final class ExampleTest extends TestCase
{
    public function test_true_is_true(): void
    {
        $this->assertTrue((new Example())->isTrue());
    }
}
