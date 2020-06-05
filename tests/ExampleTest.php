<?php

namespace Mrdth\AzureServiceBusQueue\Tests;

use Orchestra\Testbench\TestCase;
use Mrdth\AzureServiceBusQueue\ServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
