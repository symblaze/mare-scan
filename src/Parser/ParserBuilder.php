<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Parser;

use PhpParser\ParserFactory as PHPParserFactory;
use PhpParser\PhpVersion;

final class ParserBuilder
{
    private string $targetVersion = PHP_VERSION;

    public static function init(): self
    {
        return new self();
    }

    public function build(): ParserInterface
    {
        $factory = new PHPParserFactory();
        $parser = $factory->createForVersion(PhpVersion::fromString($this->targetVersion));

        return new Parser($this->targetVersion, $parser);
    }

    public function targetVersion(string $targetVersion): self
    {
        $this->targetVersion = $targetVersion;

        return $this;
    }
}
