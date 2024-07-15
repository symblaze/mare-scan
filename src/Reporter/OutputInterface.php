<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Reporter;

interface OutputInterface
{
    public function newLine(int $count = 1): void;

    public function error(string|array $message): void;

    public function warning(string|array $message): void;

    public function info(string|array $message): void;

    public function comment(string|array $message): void;

    public function table(array $headers, array $rows): void;
}
