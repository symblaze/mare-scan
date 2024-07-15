<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Config;

use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Config\Config;
use Symblaze\MareScan\Config\Finder;
use Symblaze\MareScan\Tests\TestCase;

final class ConfigTest extends TestCase
{
    #[Test]
    public function create_auto_sets_the_config_file_location(): void
    {
        $config = Config::create();

        $this->assertSame(__FILE__, $config->getConfigPath());
    }

    #[Test]
    public function it_works_as_a_finder(): void
    {
        $finder = $this->createMock(Finder::class);
        $arg = $this->faker->word();
        $config = new Config($finder);

        $finder->expects($this->once())->method('in')->with($arg);

        $config->in($arg);
    }

    #[Test]
    public function it_targets_the_run_time_php_version_by_default(): void
    {
        $config = Config::create();

        $this->assertSame(PHP_VERSION, $config->getPhpVersion());
    }

    #[Test]
    public function it_can_customize_target_php_version(): void
    {
        $version = (string)$this->faker->randomElement(['8.0', '8.1', '8.2', '8.3']);
        $config = Config::create()->targetPhpVersion($version);

        $this->assertSame($version, $config->getPhpVersion());
    }
}
