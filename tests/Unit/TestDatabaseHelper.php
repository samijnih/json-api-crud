<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

trait TestDatabaseHelper
{
    public function freshDatabase(): void
    {
        $output = new ConsoleOutput(
            OutputInterface::VERBOSITY_DEBUG,
            null,
            new OutputFormatter(),
        );
        $helperSet = new HelperSet([new DebugFormatterHelper()]);

        $process = new ProcessHelper();
        $process->setHelperSet($helperSet);
        $process->run($output, ['php', 'bin/console', 'd:d:d', '-n', '--force']);
        $process->run($output, ['php', 'bin/console', 'd:d:c', '-n']);
        $process->run($output, ['php', 'bin/console', 'd:m:m', '-n']);
    }
}
