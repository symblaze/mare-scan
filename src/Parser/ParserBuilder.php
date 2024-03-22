<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Parser;

use PhpParser\ParserFactory as PHPParserFactory;

final class ParserBuilder
{
    public static function init(): self
    {
        return new self();
    }

    public function build(): ParserInterface
    {
        $factory = new PHPParserFactory();
        $parser = $factory->createForNewestSupportedVersion();

        return new Parser(PHP_VERSION, $parser);
    }
}
