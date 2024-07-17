<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Scanner;

use ArrayIterator;
use PHPUnit\Framework\Attributes\Test;
use SplFileInfo;
use Symblaze\MareScan\Scanner\MultipleFileScanner;
use Symblaze\MareScan\Scanner\ScannerInterface;
use Symblaze\MareScan\Tests\TestCase;

final class MultipleFileScannerTest extends TestCase
{
    #[Test]
    public function scan_multiple_files(): void
    {
        $fileScanner = $this->createMock(ScannerInterface::class);
        $files = new ArrayIterator([
            $this->createMock(SplFileInfo::class),
            $this->createMock(SplFileInfo::class),
        ]);
        $sut = new MultipleFileScanner($fileScanner);

        $fileScanner->expects($this->exactly(2))->method('scan');

        $sut->scan([], ...$files);
    }
}
