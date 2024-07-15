<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Reporter;

use Symblaze\MareScan\Inspector\CodeIssue;

final readonly class DisplayTable implements ReporterInterface
{
    public function __construct(private OutputInterface $output)
    {
    }

    /**
     * @param array<string, CodeIssue[]> $result
     */
    public function report(array $result): void
    {
        $this->output->newLine(2);
        $this->output->error(sprintf('Found %d issue(s):', count($result)));
        $this->output->newLine();

        foreach ($result as $issues) {
            foreach ($issues as $issue) {
                $this->displayIssue($issue);
            }
        }
    }

    private function displayIssue(CodeIssue $issue): void
    {
        $this->output->comment($issue->getCodeLocation()->filePath);

        $headers = ['Line', 'Severity', 'Message', 'Short Message'];
        $rows = [
            [
                $issue->getCodeLocation()->lineNumber,
                $issue->getSeverity(),
                $issue->getMessage(),
                $issue->getShortMessage(),
            ],
        ];

        $this->output->table($headers, $rows);

        $this->output->newLine();
    }
}
