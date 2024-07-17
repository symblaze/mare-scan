<?php

declare(strict_types=1);

if (! function_exists('format_dir_separator')) {
    function format_dir_separator(string $path): string
    {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }
}
