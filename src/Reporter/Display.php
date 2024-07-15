<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Reporter;

use Symblaze\MareScan\Inspector\CodeIssue;

final readonly class Display implements ReporterInterface
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
        $this->title($issue);
        $this->at($issue);
        $this->shortMessage($issue);
    }

    private function title(CodeIssue $issue): void
    {
        $displayMethod = 'error' === $issue->getSeverity() ? 'error' : 'warning';
        $title = strtoupper($issue->getSeverity()).': '.$issue->getMessage();
        $this->output->$displayMethod($title);
    }

    private function at(CodeIssue $issue): void
    {
        $at = sprintf(
            'at: %s:%s',
            $issue->getCodeLocation()->filePath,
            $issue->getCodeLocation()->lineNumber,
        );
        $this->output->info($at);
    }

    private function shortMessage(CodeIssue $issue): void
    {
        $this->output->comment($issue->getShortMessage());
        $this->output->newLine();
    }
}
