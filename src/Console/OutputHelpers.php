<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Console;

use Symblaze\Console\Command;

/**
 * @mixin Command
 */
trait OutputHelpers
{
    public function newLine(int $count = 1): void
    {
        $this->output->newLine($count);
    }

    public function error(string|array $message): void
    {
        $this->output->error($message);
    }

    public function warning(string|array $message): void
    {
        $this->output->warning($message);
    }

    public function info(string|array $message): void
    {
        $this->output->info($message);
    }

    public function comment(string|array $message): void
    {
        $this->output->comment($message);
    }

    public function table(array $headers, array $rows): void
    {
        $this->output->table($headers, $rows);
    }
}
