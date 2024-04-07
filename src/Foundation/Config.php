<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Foundation;

use Symfony\Component\Finder\Finder;

final class Config
{
    private ?Finder $finder = null;

    public function getFinder(): Finder
    {
        return is_null($this->finder) ? new Finder() : $this->finder;
    }

    public function setFinder(Finder $finder): Config
    {
        $this->finder = $finder;

        return $this;
    }
}
