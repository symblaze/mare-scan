<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests\Analyzer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symblaze\MareScan\Analyzer\Issue;
use Symblaze\MareScan\Tests\TestCase;

#[CoversClass(Issue::class)]
final class IssueTest extends TestCase
{
    #[Test]
    public function create_from_array(): void
    {
        $severity = $this->faker->word();
        $message = $this->faker->sentence();
        $file = $this->faker->word();
        $description = $this->faker->sentence();

        $issue = Issue::fromArray([
            'severity' => $severity,
            'message' => $message,
            'file' => $file,
            'description' => $description,
        ]);

        $this->assertSame($severity, $issue->severity());
        $this->assertSame($message, $issue->message());
        $this->assertSame($file, $issue->file());
        $this->assertSame($description, $issue->description());
    }
}
