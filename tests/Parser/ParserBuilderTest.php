<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Parser;

use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Parser\ParserBuilder;
use Symblaze\MareScan\Tests\TestCase;

final class ParserBuilderTest extends TestCase
{
    #[Test]
    public function it_builds_a_parser_with_default_configuration(): void
    {
        $sut = ParserBuilder::init();

        $parser = $sut->build();

        $this->assertTrue(version_compare(PHP_VERSION, $parser->targetVersion(), '=='));
    }
}
