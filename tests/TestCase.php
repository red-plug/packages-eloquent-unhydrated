<?php

namespace RedPlug\EloquentUnhydrated\Tests;

use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * Get package providers.
     *
     * @api
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app)
    {
        return ['RedPlug\EloquentUnhydrated\EloquentUnhydratedServiceProvider'];
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->seedDatabase();
    }

    /**
     * Seed the initial data.
     *
     * @return void
     */
    protected function seedDatabase(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Andres Lopez',
                'email' => 'me@andreslopez.com.mx',
                'password' => 'astrongpasswordhellyeah1',
            ],
            [
                'name' => 'Taylor Otwell',
                'email' => 'taylor@laravel.com',
                'password' => 'astrongpasswordhellyeah2',
            ],
        ]);
    }
}
