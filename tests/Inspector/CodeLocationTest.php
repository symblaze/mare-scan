<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Inspector;

use PhpParser\Error;
use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Inspector\CodeLocation;
use Symblaze\MareScan\Tests\TestCase;

class CodeLocationTest extends TestCase
{
    #[Test]
    public function create_from_parser_error(): void
    {
        $this->skipOnWindows();
        $fixture = 'misc/syntax_error.stub';
        $fileInfo = $this->fixtureInfo($fixture);
        $error = new Error($this->faker->sentence(), [
            'startLine' => 3,
            'startTokenPos' => 6,
            'startFilePos' => 12,
            'endLine' => 4,
            'endTokenPos' => 6,
            'endFilePos' => 13,
        ]);

        $codeLocation = CodeLocation::fromParserError($fileInfo, $error, $this->fixture($fixture));

        $this->assertPathSame($fileInfo->getRealPath(), $codeLocation->filePath);
        $this->assertSame('syntax_error.stub', $codeLocation->fileName);
        $this->assertSame(3, $codeLocation->lineNumber);
        $this->assertSame(6, $codeLocation->columnNumber);
        $this->assertSame(4, $codeLocation->endLineNumber);
        $this->assertSame(0, $codeLocation->endColumnNumber);
    }

    #[Test]
    public function create_from_parser_error_with_missing_attributes(): void
    {
        $fixture = 'misc/syntax_error.stub';
        $fileInfo = $this->fixtureInfo($fixture);
        $error = new Error($this->faker->sentence(), []);

        $codeLocation = CodeLocation::fromParserError($fileInfo, $error, $this->fixture($fixture));

        $this->assertSame($fileInfo->getRealPath(), $codeLocation->filePath);
        $this->assertSame('syntax_error.stub', $codeLocation->fileName);
        $this->assertSame(-1, $codeLocation->lineNumber);
        $this->assertSame(-1, $codeLocation->columnNumber);
        $this->assertSame(-1, $codeLocation->endLineNumber);
        $this->assertSame(-1, $codeLocation->endColumnNumber);
    }

    #[Test]
    public function create_at_beginning_of_file(): void
    {
        $fixture = 'misc/syntax_error.stub';
        $fileInfo = $this->fixtureInfo($fixture);

        $codeLocation = CodeLocation::atBeginningOfFile($fileInfo);

        $this->assertSame($fileInfo->getRealPath(), $codeLocation->filePath);
        $this->assertSame('syntax_error.stub', $codeLocation->fileName);
        $this->assertSame(2, $codeLocation->lineNumber);
        $this->assertSame(1, $codeLocation->columnNumber);
        $this->assertSame(2, $codeLocation->endLineNumber);
        $this->assertSame(1, $codeLocation->endColumnNumber);
    }
}
