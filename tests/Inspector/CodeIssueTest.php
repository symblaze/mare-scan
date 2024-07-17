<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Inspector;

use PhpParser\Error;
use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Inspector\CodeIssue;
use Symblaze\MareScan\Inspector\CodeLocation;
use Symblaze\MareScan\Tests\TestCase;

class CodeIssueTest extends TestCase
{
    #[Test]
    public function create_from_parser_error(): void
    {
        $fixture = 'misc/syntax_error.stub';
        $fileInfo = $this->fixtureInfo($fixture);
        $error = new Error($this->faker->sentence(), []);
        $code = $this->fixture($fixture);

        $codeIssue = CodeIssue::fromParserError($error, $fileInfo, $code);

        $this->assertEquals(CodeLocation::fromParserError($fileInfo, $error, $code), $codeIssue->getCodeLocation());
        $this->assertEquals('syntax_error', $codeIssue->getType());
        $this->assertEquals($error->getMessage(), $codeIssue->getShortMessage());
        $this->assertEquals('error', $codeIssue->getSeverity());
    }

    #[Test]
    public function create_from_parser_error_with_attributes(): void
    {
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
        $code = $this->fixture($fixture);

        $codeIssue = CodeIssue::fromParserError($error, $fileInfo, $code);

        $this->assertEquals($error->getMessageWithColumnInfo($code), $codeIssue->getShortMessage());
    }
}
