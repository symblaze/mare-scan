<?php

declare(strict_types=1);

function scalar_is_empty(int|float|string|bool|null $value): bool
{
    return empty($value);
}

function int_is_empty(int $value): bool
{
    return empty($value);
}

function float_is_empty(float $value): bool
{
    return empty($value);
}

function string_is_empty(string $value): bool
{
    return empty($value);
}

function bool_is_empty(bool $value): bool
{
    return empty($value);
}

function null_is_empty(null $value): bool
{
    return empty($value);
}
