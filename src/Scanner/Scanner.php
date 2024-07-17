<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Scanner;

use Closure;
use SplFileInfo;
use Symblaze\MareScan\Config\Config;
use Symblaze\MareScan\Inspector\CodeIssue;

final readonly class Scanner implements ScannerInterface
{
    public function __construct(
        private Config $config,
        /** @var MultipleFileScanner */
        private ScannerInterface $scanner
    ) {
    }

    public static function create(Config $config, ?Closure $callback = null): self
    {
        $fileScanner = new FileScanner($config->getParser(), $callback);
        $multipleFileScanner = new MultipleFileScanner($fileScanner);

        return new self($config, $multipleFileScanner);
    }

    /**
     * @return array<string, CodeIssue[]>
     */
    public function scan(?array $inspectors = null, ?SplFileInfo $file = null): array
    {
        return $this->scanner->scan($this->config->getInspectors(), ...$this->config->getFinder()->getIterator());
    }
}
