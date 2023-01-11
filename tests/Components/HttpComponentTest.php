<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

it('installs the required packages', function () {
    $composerMock = $this->createMock(ComposerContract::class);

    $composerMock->expects($this->exactly(2))
        ->method('require')
        ->withConsecutive(
            [$this->equalTo('guzzlehttp/guzzle "^7.5"')],
            [$this->equalTo('illuminate/http "^10.0"')]
        );

    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'http']);
});

it('can use the http client', function () {
    Http::fake();

    $response = Http::withHeaders([
        'X-Faked' => 'enabled',
    ])->get('https://faked.test');

    Http::assertSent(static function ($request) {
        return $request->hasHeader('X-Faked', 'enabled')
            && $request->url('https://faked.test');
    });

    expect($response->ok())->toBeTrue()
        ->and($response->body())->toBeEmpty();
});
