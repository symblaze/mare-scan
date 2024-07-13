<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Console;

use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Console\ConfigFinder;
use Symblaze\MareScan\Exception\ConfigNotFoundException;
use Symblaze\MareScan\Tests\TestCase;

final class ConfigFinderTest extends TestCase
{
    #[Test]
    public function it_can_find_config(): void
    {
        $configPath = (string)realpath(__DIR__.'/../fixtures/.mare_scan.php');
        $sut = new ConfigFinder();

        $actual = $sut->find($configPath, $this->rootDir());

        $this->assertPathSame($configPath, $actual->getConfigPath());
    }

    #[Test]
    public function it_should_fallback_to_default_config_file_path(): void
    {
        $sut = new ConfigFinder();

        $actual = $sut->find('', $this->rootDir());

        $this->assertPathSame($this->rootDir().'/.mare_scan.php', $actual->getConfigPath());
    }

    #[Test]
    public function it_should_fail_if_config_file_not_found(): void
    {
        $sut = new ConfigFinder();

        $this->expectException(ConfigNotFoundException::class);

        $sut->find('not_found.php', $this->rootDir());
    }

    #[Test]
    public function it_should_fail_if_fallback_config_not_found(): void
    {
        $sut = new ConfigFinder();

        $this->expectException(ConfigNotFoundException::class);

        $sut->find('', '/not_found');
    }
}
