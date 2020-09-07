<?php

namespace LaravelZero\Framework\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\StubPublishCommand as BaseStubPublishCommand;

class StubPublishCommand extends BaseStubPublishCommand
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (! is_dir($stubsPath = $this->laravel->basePath('stubs'))) {
            (new Filesystem)->makeDirectory($stubsPath);
        }

        $files = [
            __DIR__.'/stubs/console.stub' => $stubsPath.'/console.stub',
        ];

        foreach ($files as $from => $to) {
            if (! file_exists($to) || $this->option('force')) {
                file_put_contents($to, file_get_contents($from));
            }
        }

        $this->info('Stubs published successfully.');
    }
}
