<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Exception;

use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Exception\InspectionError;
use Symblaze\MareScan\Tests\TestCase;

/**
 * @covers \Symblaze\MareScan\Exception\InspectionError
 */
final class InspectionErrorTest extends TestCase
{
    #[Test]
    public function severity_levels(): void
    {
        $this->assertSame(1, InspectionError::SEVERITY_ERROR);
        $this->assertSame(2, InspectionError::SEVERITY_WARNING);
    }

    #[Test]
    public function error_severity(): void
    {
        $error = InspectionError::error('Error message');

        $this->assertSame('Error message', $error->getMessage());
        $this->assertSame(1, $error->getCode());
    }

    #[Test]
    public function warning_severity(): void
    {
        $warning = InspectionError::warning('Warning message');

        $this->assertSame('Warning message', $warning->getMessage());
        $this->assertSame(2, $warning->getCode());
    }
}
