<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Scanner;

use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Config\Config;
use Symblaze\MareScan\Scanner\FileScanner;
use Symblaze\MareScan\Scanner\MultipleFileScanner;
use Symblaze\MareScan\Scanner\Scanner;
use Symblaze\MareScan\Tests\TestCase;

final class ScannerTest extends TestCase
{
    #[Test]
    public function it_should_scan_configured_files(): void
    {
        $config = Config::create()->in(__DIR__.'/../fixtures/scan_command')->files();
        $fileScanner = new FileScanner($config->getParser());
        $multipleFileScanner = new MultipleFileScanner($fileScanner);
        $sut = new Scanner($config, $multipleFileScanner);

        $result = $sut->scan();

        $this->assertCount(2, $result);
        $issue1 = reset($result)[0];
        $issue2 = end($result)[0];
        $this->assertEqualsCanonicalizing(
            ['missing_strict_types_declaration', 'syntax_error'],
            [$issue1->getType(), $issue2->getType()]
        );
    }
}
