<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Tests;

use ArrayIterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SplFileInfo;
use Symblaze\Console\IO\Output;
use Symblaze\MareScan\Analyzer\AnalyzerInterface;
use Symblaze\MareScan\Foundation\Config;
use Symblaze\MareScan\Foundation\ConfigFinder;
use Symblaze\MareScan\Foundation\Finder;
use Symblaze\MareScan\ScanCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

#[CoversClass(ScanCommand::class)]
final class ScanCommandTest extends TestCase
{
    #[Test]
    public function it_shows_information_message_when_starts(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(Output::class);
        $configFinder = $this->createMock(ConfigFinder::class);
        $config = new Config($this->createMock(Finder::class));
        $configFinder->method('find')->willReturn($config);
        $sut = new ScanCommand($configFinder);

        $output->expects(self::once())->method('info')->with('Mare scan is running...');

        $sut->run($input, $output);
    }

    #[Test]
    public function configuration_is_required(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(Output::class);
        $configFinder = $this->createMock(ConfigFinder::class);
        $sut = new ScanCommand($configFinder);

        $output->expects(self::once())->method('error')->with('No configuration file found');

        $sut->run($input, $output);
    }

    #[Test]
    public function it_should_analyze_all_matched_files(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(Output::class);
        $analyzer = $this->createMock(AnalyzerInterface::class);
        $configFinder = $this->createMock(ConfigFinder::class);
        $finder = $this->createMock(Finder::class);
        $files = [];
        $filesCount = random_int(1, 10);
        for ($i = 1; $i <= $filesCount; ++$i) {
            $file = $this->faker->word().'.php';
            $files[] = new SplFileInfo($file);
        }
        $finder->method('getIterator')->willReturn(new ArrayIterator($files));
        $config = new Config($finder, $analyzer);
        $configFinder->method('find')->willReturn($config);
        $sut = new ScanCommand($configFinder);

        $analyzer->expects(self::exactly($filesCount))->method('analyze');

        $sut->run($input, $output);
    }

    #[Test]
    public function it_should_succeed_if_no_issues_found(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(Output::class);
        $configFinder = $this->createMock(ConfigFinder::class);
        $config = new Config($this->createMock(Finder::class));
        $configFinder->method('find')->willReturn($config);
        $sut = new ScanCommand($configFinder);

        $output->expects(self::once())->method('success')->with('No issues found');

        $sut->run($input, $output);
    }

    #[Test]
    public function configuration_file_can_be_chosen(): void
    {
        $configPath = __DIR__.'/fixtures/.mare_scan.php';
        $input = $this->createMock(InputInterface::class);
        $input->method('getOption')->with('config')->willReturn($configPath);
        $output = $this->createMock(Output::class);
        $configFinder = new ConfigFinder();
        $sut = new ScanCommand($configFinder);

        $status = $sut->run($input, $output);

        $this->assertSame(Command::FAILURE, $status);
    }

    #[Test]
    public function it_displays_found_issues_count(): void
    {
        $configPath = __DIR__.'/fixtures/.mare_scan.php';
        $input = $this->createMock(InputInterface::class);
        $input->method('getOption')->with('config')->willReturn($configPath);
        $output = $this->createMock(Output::class);
        $configFinder = new ConfigFinder();
        $sut = new ScanCommand($configFinder);

        $output->expects(self::once())->method('error')->with('Total issues found: 1');

        $sut->run($input, $output);
    }

    #[Test]
    public function it_displays_issues_details(): void
    {
        $configPath = __DIR__.'/fixtures/.mare_scan.php';
        $input = $this->createMock(InputInterface::class);
        $input->method('getOption')->with('config')->willReturn($configPath);
        $output = $this->createMock(Output::class);
        $configFinder = new ConfigFinder();
        $sut = new ScanCommand($configFinder);

        $output->expects(self::once())->method('warning')->with(
            'error: Syntax error, unexpected T_ENCAPSED_AND_WHITESPACE on line 3'
        );

        $sut->run($input, $output);
    }

    #[Test]
    public function it_displays_the_issues_location(): void
    {
        $configPath = __DIR__.'/fixtures/.mare_scan.php';
        $input = $this->createMock(InputInterface::class);
        $input->method('getOption')->with('config')->willReturn($configPath);
        $output = $this->createMock(Output::class);
        $configFinder = new ConfigFinder();
        $sut = new ScanCommand($configFinder);

        $output->expects(self::exactly(2))->method('info')->with(
            $this->callback(function (string $message) {
                $expectedArgs = [
                    'Mare scan is running...',
                    'at: '.$this->fixturePath('scan_command/syntax_error.stub'),
                ];

                return in_array($message, $expectedArgs, true);
            })
        );

        $sut->run($input, $output);
    }
}
