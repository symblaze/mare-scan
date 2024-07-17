<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Parser;

use PhpParser\Node\Stmt\Echo_;
use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Inspector\CodeIssue;
use Symblaze\MareScan\Parser\ParserBuilder;
use Symblaze\MareScan\Tests\TestCase;

/**
 * @covers \Symblaze\MareScan\Parser\Parser
 */
final class ParserTest extends TestCase
{
    #[Test]
    public function parse_valid_code(): void
    {
        $file = $this->fixtureInfo('misc/single_statement.php');
        $sut = ParserBuilder::init()->build();

        $actual = $sut->parse($file);

        $this->assertCount(1, $actual);
        $this->assertInstanceOf(Echo_::class, $actual[0]);
    }

    #[Test]
    public function parse_invalid_code(): void
    {
        $file = $this->fixtureInfo('misc/syntax_error.stub');
        $sut = ParserBuilder::init()->build();

        $this->expectException(CodeIssue::class);

        $sut->parse($file);
    }
}
