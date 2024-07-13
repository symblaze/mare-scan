<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Console;

use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Console\ConfigFinder;
use Symblaze\MareScan\Console\IOInterface;
use Symblaze\MareScan\Exception\ConfigNotFoundException;
use Symblaze\MareScan\Tests\TestCase;

final class ConfigFinderTest extends TestCase
{
    #[Test]
    public function it_can_find_config(): void
    {
        $input = $this->createMock(IOInterface::class);
        $configPath = realpath(__DIR__.'/../fixtures/.mare_scan.php');
        $input->method('getOption')->willReturn($configPath);
        $sut = new ConfigFinder();

        $actual = $sut->find($input);

        $this->assertSame($configPath, $actual->getConfigPath());
    }

    #[Test]
    public function it_should_fallback_to_default_config_file_path(): void
    {
        $input = $this->createMock(IOInterface::class);
        $input->method('getOption')->willReturn(null);
        $input->method('workDirectory')->willReturn($this->rootDir());
        $sut = new ConfigFinder();

        $actual = $sut->find($input);

        $this->assertSame($this->rootDir().'/.mare_scan.php', $actual->getConfigPath());
    }

    #[Test]
    public function it_should_fail_if_config_file_not_found(): void
    {
        $input = $this->createMock(IOInterface::class);
        $input->method('getOption')->willReturn('not_found.php');
        $sut = new ConfigFinder();

        $this->expectException(ConfigNotFoundException::class);

        $sut->find($input);
    }

    #[Test]
    public function it_should_fail_if_fallback_config_not_found(): void
    {
        $input = $this->createMock(IOInterface::class);
        $input->method('getOption')->willReturn(null);
        $input->method('workDirectory')->willReturn($this->rootDir().'/not_found');
        $sut = new ConfigFinder();

        $this->expectException(ConfigNotFoundException::class);

        $sut->find($input);
    }
}
