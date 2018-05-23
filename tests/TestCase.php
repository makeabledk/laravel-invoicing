<?php

namespace Makeable\LaravelInvoicing\Tests;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;
use Makeable\LaravelCurrencies\Amount;
use Makeable\LaravelInvoicing\InvoiceDocument;
use Makeable\LaravelInvoicing\Providers\InvoicingServiceProvider;
use Makeable\LaravelInvoicing\Tests\Stubs\Customer;
use Makeable\LaravelInvoicing\Tests\Stubs\Product;

class TestCase extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpFactories($this->app);

        Amount::test();
        Storage::fake();
        InvoiceDocument::fake();
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        putenv('APP_ENV=testing');
        putenv('APP_DEBUG=true');
        putenv('DB_CONNECTION=sqlite');
        putenv('DB_DATABASE=:memory:');

        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->useEnvironmentPath(__DIR__.'/..');
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        $app->register(InvoicingServiceProvider::class);
        $app->afterResolving('migrator', function ($migrator) {
            $migrator->path(__DIR__.'/migrations/');
        });

        // Register facade
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('InvoiceDocument', InvoiceDocument::class);

        return $app;
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpFactories($app)
    {
        $app->make(Factory::class)->define(Customer::class, function ($faker) {
            return [
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt('foo'),
            ];
        });
    }

    /**
     * @return Customer
     */
    protected function customer()
    {
        return factory(Customer::class)->create();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function products()
    {
        return collect([
            Product::create([]),
            Product::create([]),
        ]);
    }
}
