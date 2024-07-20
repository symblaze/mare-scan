<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Inspector\CodeStyle;

use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Inspector\CodeStyle\EmptyFunctionUsageInspector;
use Symblaze\MareScan\Tests\TestCase;

final class EmptyFunctionUsageInspectorTest extends TestCase
{
    #[Test]
    public function definition(): void
    {
        $sut = new EmptyFunctionUsageInspector();

        $this->assertSame('empty_function_usage', $sut->shortName());
        $this->assertSame('Empty function usage', $sut->displayName());
        $this->assertSame('Detects the usage of `empty()` function.', $sut->description());
    }

    #[Test]
    public function inspect_empty_usage(): void
    {
        $fixture = $this->fixtureInfo('code_style/empty_used_with_scalars.php');
        $sut = new EmptyFunctionUsageInspector();

        $result = $sut->inspect($fixture, ...$this->parse($fixture));

        $this->assertCount(6, $result);
    }
}
