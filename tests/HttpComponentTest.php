<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

final class HttpComponentTest extends TestCase
{
    /** @test */
    public function it_installs_the_required_packages(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);

        $composerMock->expects($this->exactly(2))
            ->method('require')
            ->withConsecutive(
                [$this->equalTo('guzzlehttp/guzzle "^6.3.1"')],
                [$this->equalTo('illuminate/http "^7.0"')]
            );

        $this->app->instance(ComposerContract::class, $composerMock);

        Artisan::call('app:install', ['component' => 'http']);
    }

    /** @test */
    public function it_can_use_the_http_client(): void
    {
        Http::fake();

        $response = Http::withHeaders([
            'X-Faked' => 'enabled',
        ])->get('https://faked.test');

        Http::assertSent(function ($request) {
            return $request->hasHeader('X-Faked', 'enabled')
                && $request->url('https://faked.test');
        });

        $this->assertTrue($response->ok());
        $this->assertEmpty($response->body());
    }
}
