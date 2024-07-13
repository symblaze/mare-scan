<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Exception;

use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Exception\ConfigNotFoundException;
use Symblaze\MareScan\Tests\TestCase;

final class ConfigNotFoundExceptionTest extends TestCase
{
    #[Test]
    public function create(): void
    {
        $exception = ConfigNotFoundException::create('path/to/config.php');

        $this->assertSame('Config file not found at path/to/config.php', $exception->getMessage());
    }
}
