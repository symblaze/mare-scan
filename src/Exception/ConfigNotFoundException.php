<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Exception;

final class ConfigNotFoundException extends MareScanException
{
    public static function create(string $configPath): self
    {
        return new self('Config file not found at '.$configPath);
    }
}
