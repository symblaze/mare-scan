<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests;

use Faker\Factory;
use Faker\Generator;
use PhpParser\Node\Stmt;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use SplFileInfo;
use Symblaze\MareScan\Parser\ParserBuilder;

/**
 * All unit tests should extend this class.
 */
abstract class TestCase extends PHPUnitTestCase
{
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    protected function rootDir(): string
    {
        return $this->unifyDirectorySeparator((string)realpath(__DIR__.'/..'));
    }

    protected function fixture(string $path): string
    {
        $contents = file_get_contents($this->fixturePath($path));
        assert(is_string($contents), 'Fixture not found.');

        return $contents;
    }

    protected function fixtureInfo(string $path): SplFileInfo
    {
        return new SplFileInfo($this->fixturePath($path));
    }

    protected function fixturePath(string $path): string
    {
        $realpath = realpath(__DIR__.'/fixtures/'.$path);
        assert(is_string($realpath), 'Fixture not found.');

        return $this->unifyDirectorySeparator($realpath);
    }

    private function unifyDirectorySeparator(string $path): string
    {
        return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
    }

    protected function assertPathSame(string $expected, string $actual): void
    {
        $this->assertSame($this->unifyDirectorySeparator($expected), $this->unifyDirectorySeparator($actual));
    }

    /**
     * @return Stmt[]
     */
    protected function parse(SplFileInfo $file): array
    {
        return ParserBuilder::init()->build()->parse($file);
    }

    protected function skipOnWindows(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Test skipped on Windows.');
        }
    }
}
