<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Scanner;

use SplFileInfo;
use Symblaze\MareScan\Config\Config;

final readonly class Scanner implements ScannerInterface
{
    public function __construct(
        private Config $config,
        /** @var MultipleFileScanner */
        private ScannerInterface $scanner
    ) {
    }

    public function scan(?array $inspectors = null, ?SplFileInfo $file = null): array
    {
        return $this->scanner->scan($this->config->getInspectors(), ...$this->config->getFinder()->getIterator());
    }
}
