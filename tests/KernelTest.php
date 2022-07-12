<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Application;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

it('binds the output into the container', function () {
    Artisan::handle(new ArrayInput([]), $output = new NullOutput());

    expect($output)->toEqual(Application::getInstance()->get(OutputInterface::class));
});

it('binds the logger into the container', function () {
    expect(app('log'))->toBeInstanceOf(LoggerInterface::class);
});
