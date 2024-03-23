<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Inspector\TypeCompatibility;

use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Exception\InspectionError;
use Symblaze\MareScan\Inspector\TypeCompatibility\MissingDeclareStrictTypesInspector;
use Symblaze\MareScan\Parser\ParserBuilder;
use Symblaze\MareScan\Tests\TestCase;

/**
 * @covers \Symblaze\MareScan\Inspector\TypeCompatibility\MissingDeclareStrictTypesInspector
 */
final class MissingDeclareStrictTypesInspectorTest extends TestCase
{
    #[Test]
    public function it_has_a_name(): void
    {
        $sut = new MissingDeclareStrictTypesInspector();

        $name = $sut->name();

        self::assertSame('Missing declare strict types', $name);
    }

    #[Test]
    public function it_has_a_description(): void
    {
        $sut = new MissingDeclareStrictTypesInspector();

        $description = $sut->description();

        $needle = 'Detects the missing declare(strict_types=1) directive in the file.';
        self::assertStringContainsString($needle, $description);
    }

    #[Test]
    public function it_should_accept_valid_declare_strict_types(): void
    {
        $this->expectNotToPerformAssertions();

        $code = $this->fixture('type_compatibility/valid_declare_strict_types.php');
        $parser = ParserBuilder::init()->build();
        $ast = $parser->parse($code);
        $sut = new MissingDeclareStrictTypesInspector($ast[0] ?? null);

        $sut->inspect();
    }

    #[Test]
    public function it_should_reject_missing_declare_strict_types(): void
    {
        $this->expectException(InspectionError::class);

        $code = $this->fixture('type_compatibility/missing_declare_strict_types.php');
        $parser = ParserBuilder::init()->build();
        $ast = $parser->parse($code);
        $sut = new MissingDeclareStrictTypesInspector($ast[0] ?? null);

        $sut->inspect();
    }

    #[Test]
    public function it_should_reject_invalid_declare_strict_types(): void
    {
        $this->expectException(InspectionError::class);

        $code = $this->fixture('type_compatibility/invalid_declare_strict_types.php');
        $parser = ParserBuilder::init()->build();
        $ast = $parser->parse($code);
        $sut = new MissingDeclareStrictTypesInspector($ast[0] ?? null);

        $sut->inspect();
    }
}
