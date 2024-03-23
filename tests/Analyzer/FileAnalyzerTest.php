<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Analyzer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Analyzer\FileAnalyzer;
use Symblaze\MareScan\Inspector\TypeCompatibility\MissingDeclareStrictTypesInspector;
use Symblaze\MareScan\Parser\ParserBuilder;
use Symblaze\MareScan\Tests\TestCase;

#[CoversClass(FileAnalyzer::class)]
final class FileAnalyzerTest extends TestCase
{
    #[Test]
    public function it_can_detect_syntax_error(): void
    {
        $filePath = $this->fixturePath('misc/syntax_error.stub');
        $parser = ParserBuilder::init()->build();
        $sut = new FileAnalyzer($parser);
        $data = [
            'path' => $filePath,
            'type' => 'file',
        ];

        $result = $sut->analyze($data);

        $expected = [
            [
                'severity' => 'error',
                'description' => 'Parser error occurred while parsing the file',
                'message' => 'Syntax error, unexpected T_ENCAPSED_AND_WHITESPACE on line 3',
                'file' => $filePath,
            ],
        ];
        $this->assertEqualsCanonicalizing($expected, $result);
    }

    #[Test]
    public function it_can_detect_missing_strict_types_declaration(): void
    {
        $filePath = $this->fixturePath('type_compatibility/missing_declare_strict_types.php');
        $parser = ParserBuilder::init()->build();
        $data = [
            'path' => $filePath,
            'type' => 'file',
        ];
        $sut = new FileAnalyzer($parser);

        $result = $sut->analyze($data);

        $inspector = new MissingDeclareStrictTypesInspector();
        $expected = [
            [
                'severity' => 'warning',
                'message' => 'Strict types declaration is missing',
                'file' => $filePath,
                'description' => $inspector->description(),
            ],
        ];
        $this->assertEqualsCanonicalizing($expected, $result);
    }

    #[Test]
    public function it_can_detect_invalid_declare_strict_types(): void
    {
        $filePath = $this->fixturePath('type_compatibility/invalid_declare_strict_types.php');
        $parser = ParserBuilder::init()->build();
        $data = [
            'path' => $filePath,
            'type' => 'file',
        ];
        $sut = new FileAnalyzer($parser);

        $result = $sut->analyze($data);

        $inspector = new MissingDeclareStrictTypesInspector();
        $expected = [
            [
                'severity' => 'warning',
                'message' => 'Strict types declaration is missing',
                'file' => $filePath,
                'description' => $inspector->description(),
            ],
        ];
        $this->assertEqualsCanonicalizing($expected, $result);
    }

    #[Test]
    public function it_can_detect_valid_declare_strict_types(): void
    {
        $filePath = $this->fixturePath('type_compatibility/valid_declare_strict_types.php');
        $parser = ParserBuilder::init()->build();
        $data = [
            'path' => $filePath,
            'type' => 'file',
        ];
        $sut = new FileAnalyzer($parser);

        $result = $sut->analyze($data);

        $this->assertEmpty($result);
    }
}
