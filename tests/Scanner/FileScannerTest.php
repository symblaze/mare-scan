<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Scanner;

use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Inspector\CodeIssue;
use Symblaze\MareScan\Inspector\InspectorInterface;
use Symblaze\MareScan\Parser\ParserBuilder;
use Symblaze\MareScan\Scanner\FileScanner;
use Symblaze\MareScan\Tests\TestCase;

final class FileScannerTest extends TestCase
{
    #[Test]
    public function scan_a_file_with_syntax_error(): void
    {
        $file = $this->fixtureInfo('misc/syntax_error.stub');
        $parser = ParserBuilder::init()->build();
        $sut = new FileScanner($parser);

        $issues = $sut->scan([], $file);

        $this->assertCount(1, $issues);
        $this->assertSame('syntax_error', $issues[0]->getType());
    }

    #[Test]
    public function it_should_run_all_inspectors(): void
    {
        $file = $this->fixtureInfo('misc/single_statement.php');
        $parser = ParserBuilder::init()->build();
        $statements = $parser->parse($file);
        $inspector1 = $this->createMock(InspectorInterface::class);
        $inspector2 = $this->createMock(InspectorInterface::class);
        $sut = new FileScanner($parser);

        $inspector1->expects($this->once())->method('inspect')->with($file, ...$statements);
        $inspector2->expects($this->once())->method('inspect')->with($file, ...$statements);

        $sut->scan([$inspector1, $inspector2], $file);
    }

    #[Test]
    public function it_should_call_the_callback_if_exists(): void
    {
        $file = $this->fixtureInfo('misc/single_statement.php');
        $parser = ParserBuilder::init()->build();
        $codeIssue = $this->createMock(CodeIssue::class);
        $inspector = $this->createMock(InspectorInterface::class);
        $inspector->method('inspect')->willReturn([$codeIssue]);
        $callback = $this->createMock(Callback::class);
        $sut = new FileScanner($parser, fn ($f, $s) => $callback->__invoke($f, $s));

        $callback->expects($this->once())->method('__invoke')->with($file, [$codeIssue]);

        $sut->scan([$inspector], $file);
    }
}

class Callback
{
    public function __invoke($f, $s)
    {
    }
}
