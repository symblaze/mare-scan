<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests;

use PHPUnit\Framework\Attributes\Test;

trait MarkTestSkipped
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->markTestSkipped('Not implemented yet.');
    }

    #[Test]
    public function true_is_true(): void
    {
        $this->assertTrue(true);
    }
}
