<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Foundation;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Foundation\Config;
use Symblaze\MareScan\Foundation\Finder;
use Symblaze\MareScan\Tests\TestCase;

#[CoversClass(Config::class)]
final class ConfigTest extends TestCase
{
    #[Test]
    public function it_initializes_the_finder(): void
    {
        $config = Config::create();

        $firstCall = $config->getFinder();
        $secondCall = $config->getFinder();

        $this->assertSame($firstCall, $secondCall);
    }

    #[Test]
    public function it_pass_calls_to_the_finder(): void
    {
        $randomDir = $this->faker->word();
        $finder = $this->createMock(Finder::class);
        $config = new Config($finder);

        $finder->expects($this->once())->method('in')->with($randomDir);

        $config->in($randomDir);
    }

    #[Test]
    public function it_initializes_the_analyzer(): void
    {
        $config = Config::create();

        $firstCall = $config->getAnalyzer();
        $secondCall = $config->getAnalyzer();

        $this->assertSame($firstCall, $secondCall);
    }

    #[Test]
    public function it_can_config_php_version(): void
    {
        $randomVersion = (string)$this->faker->randomElement(['7.4', '8.0', '8.1', '8.2']);
        $config = Config::create();

        $config->targetPhpVersion($randomVersion);

        $this->assertSame($randomVersion, $config->getPhpVersion());
        $this->assertSame($randomVersion, $config->getParser()->targetVersion());
    }
}
