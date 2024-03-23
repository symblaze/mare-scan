<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Exception;

final class InspectionError extends MareScanException
{
    public const int SEVERITY_ERROR = 1;
    public const int SEVERITY_WARNING = 2;

    public function __construct(string $message = '', int $code = 0)
    {
        parent::__construct($message, $code);
    }

    public static function error(string $message): self
    {
        return new self($message, self::SEVERITY_ERROR);
    }

    public static function warning(string $message): self
    {
        return new self($message, self::SEVERITY_WARNING);
    }
}
