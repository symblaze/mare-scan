<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Analyzer;

final readonly class Issue
{
    public function __construct(
        private string $severity,
        private string $message,
        private string $file,
        private string $description
    ) {
    }

    public static function fromArray(array $data): self
    {
        assert(array_key_exists('severity', $data));
        assert(array_key_exists('message', $data));
        assert(array_key_exists('file', $data));
        assert(array_key_exists('description', $data));

        return new self(
            (string)$data['severity'],
            (string)$data['message'],
            (string)$data['file'],
            (string)$data['description']
        );
    }

    public function severity(): string
    {
        return $this->severity;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function file(): string
    {
        return $this->file;
    }

    public function description(): string
    {
        return $this->description;
    }
}
