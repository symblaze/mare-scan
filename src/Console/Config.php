<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Console;

use Symblaze\MareScan\Analyzer\AnalyzerInterface;
use Symblaze\MareScan\Analyzer\FileAnalyzer;
use Symblaze\MareScan\Parser\ParserBuilder;
use Symblaze\MareScan\Parser\ParserInterface;

/**
 * @mixin Finder
 */
final class Config
{
    private string $phpVersion = PHP_VERSION;

    public function __construct(
        private ?Finder $finder = null,
        private ?AnalyzerInterface $analyzer = null,
        private ?string $configPath = null
    ) {
    }

    public static function create(): self
    {
        $config = new self();

        $trace = debug_backtrace();
        assert(isset($trace[0]['file']));
        $caller = $trace[0]['file'];
        $config->configPath = $caller;

        return $config;
    }

    public function getFinder(): Finder
    {
        $this->finder ??= new Finder();

        return $this->finder;
    }

    public function getAnalyzer(): AnalyzerInterface
    {
        if (! is_null($this->analyzer)) {
            return $this->analyzer;
        }

        $this->analyzer = new FileAnalyzer($this->getParser());

        return $this->analyzer;
    }

    public function getParser(): ParserInterface
    {
        return ParserBuilder::init()->targetVersion($this->getPhpVersion())->build();
    }

    public function __call(string $name, array $arguments): self
    {
        if (method_exists($this->getFinder(), $name)) {
            $this->getFinder()->$name(...$arguments);
        }

        return $this;
    }

    public function targetPhpVersion(string $targetVersion): self
    {
        $this->phpVersion = $targetVersion;

        return $this;
    }

    public function getPhpVersion(): string
    {
        return $this->phpVersion;
    }

    public function getConfigPath(): ?string
    {
        return $this->configPath;
    }
}
