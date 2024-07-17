<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Inspector\TypeCompatibility;

use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Inspector\TypeCompatibility\MissingStrictTypesDeclarationInspector;
use Symblaze\MareScan\Tests\TestCase;

final class MissingStrictTypesDeclarationInspectorTest extends TestCase
{
    #[Test]
    public function definition(): void
    {
        $sut = new MissingStrictTypesDeclarationInspector();

        $this->assertSame('missing_strict_types_declaration', $sut->shortName());
        $this->assertSame('Missing strict types declaration', $sut->displayName());
        $this->assertSame('Detects the missing declare(strict_types=1) directive in the file.', $sut->description());
    }

    #[Test]
    public function inspect_empty_file_with_tag(): void
    {
        $file = $this->fixtureInfo('misc/empty_with_tag.php');
        $sut = new MissingStrictTypesDeclarationInspector();

        $issues = $sut->inspect($file, ...$this->parse($file));

        $this->assertCount(1, $issues);
        $this->assertSame($sut->shortName(), $issues[0]->getType());
    }

    #[Test]
    public function inspect_file_starts_with_declare_but_not_strict(): void
    {
        $file = $this->fixtureInfo('type_compatibility/declare_not_strict.php');
        $sut = new MissingStrictTypesDeclarationInspector();

        $issues = $sut->inspect($file, ...$this->parse($file));

        $this->assertCount(1, $issues);
        $this->assertSame($sut->shortName(), $issues[0]->getType());
    }

    #[Test]
    public function inspect_file_with_missing_declare_strict_types(): void
    {
        $file = $this->fixtureInfo('type_compatibility/missing_declare_strict_types.php');
        $sut = new MissingStrictTypesDeclarationInspector();

        $issues = $sut->inspect($file, ...$this->parse($file));

        $this->assertCount(1, $issues);
        $this->assertSame($sut->shortName(), $issues[0]->getType());
    }

    #[Test]
    public function inspect_file_with_invalid_declare_strict_types(): void
    {
        $file = $this->fixtureInfo('type_compatibility/invalid_declare_strict_types.php');
        $sut = new MissingStrictTypesDeclarationInspector();

        $issues = $sut->inspect($file, ...$this->parse($file));

        $this->assertCount(1, $issues);
        $this->assertSame($sut->shortName(), $issues[0]->getType());
    }

    #[Test]
    public function inspect_file_with_valid_declare_strict_types(): void
    {
        $file = $this->fixtureInfo('type_compatibility/valid_declare_strict_types.php');
        $sut = new MissingStrictTypesDeclarationInspector();

        $issues = $sut->inspect($file, ...$this->parse($file));

        $this->assertCount(0, $issues);
    }
}
