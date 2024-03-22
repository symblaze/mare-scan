<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Parser;

use PhpParser\Node\Stmt\Echo_;
use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Exception\ParserError;
use Symblaze\MareScan\Parser\ParserBuilder;
use Symblaze\MareScan\Tests\TestCase;

/**
 * @covers \Symblaze\MareScan\Parser\Parser
 */
final class ParserTest extends TestCase
{
    #[Test]
    public function it_can_parse_code(): void
    {
        $code = $this->fixture('misc/single_statement.php');
        $parser = ParserBuilder::init()->build();

        $result = $parser->parse($code);

        $this->assertCount(1, $result);
        $this->assertInstanceOf(Echo_::class, $result[0]);
    }

    #[Test]
    public function it_throws_parser_error_on_invalid_code(): void
    {
        $code = '<?php $a = ;';
        $parser = ParserBuilder::init()->build();

        $this->expectException(ParserError::class);

        $parser->parse($code);
    }
}
