<?php

declare(strict_types=1);

namespace Tests;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use LaravelZero\Framework\Application;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

final class KernelTest extends TestCase
{
    public function testBindedOutputIntoContainer(): void
    {
        Artisan::handle(new ArrayInput([]), $output = new NullOutput);

        $this->assertEquals(
            $output,
            Application::getInstance()
                ->get(OutputInterface::class)
        );
    }

    public function testBindedNullLoggerIntoContainer(): void
    {
        $this->assertInstanceOf(NullLogger::class, app(LoggerInterface::class));
    }
}
