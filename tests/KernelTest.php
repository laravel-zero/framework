<?php

declare(strict_types=1);

namespace Tests;

use LaravelZero\Framework\Application;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

final class KernelTest extends TestCase
{
    /** @test */
    public function it_binds_the_output_into_the_container(): void
    {
        Artisan::handle(new ArrayInput([]), $output = new NullOutput);

        $this->assertEquals(
            $output,
            Application::getInstance()
                ->get(OutputInterface::class)
        );
    }
}
