<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Tests\Unit;

use Tests\TestCase;

/**
 * This is the Zero Framework application test class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class ApplicationTest extends TestCase
{
    /**
     * Tests if the application proxies correctly unknown methods
     * into the application container.
     *
     * @return void
     */
    public function testProxyCall(): void
    {
        $app = $this->createApplication();

        $app->setContainer(
            $mock = $this->createMock('\Illuminate\Container\Container')
        );

        $mock->expects($this->exactly(1))
            ->method('bind')
            ->with(
                $this->stringContains('something'),
                $this->callback(
                    function () {
                        return 'foo';
                    }
                )
            );

        $app->bind(
            'something',
            function () {
                return 'foo';
            }
        );
    }

    /**
     * Tests if the application proxies correctly is array access.
     * into the application container.
     *
     * @return void
     */
    public function testProxyArrayAccess(): void
    {
        $app = $this->createApplication();

        $app['something'] = function () {
            return 'foo';
        };
        $this->assertTrue(isset($app['something']));
        $this->assertEquals('foo', $app['something']);
        unset($app['something']);
        $this->assertFalse(isset($app['something']));
    }
}
