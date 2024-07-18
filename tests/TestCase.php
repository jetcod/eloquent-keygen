<?php

namespace Jetcod\Eloquent\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Jetcod\Eloquent\ServiceProvider;
use Mockery as m;
use Orchestra\Testbench\TestCase as PHPUnitTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class TestCase extends PHPUnitTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        m::close();
    }

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
    }

    protected function getEnvironmentSetUp($app) {}
}
