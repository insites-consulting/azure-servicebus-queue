<?php


namespace Mrdth\AzureServiceBusQueue\Tests;

use Dotenv\Dotenv;
use Illuminate\Support\Facades\Queue;
use Mrdth\AzureServiceBusQueue\AzureQueue;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Mrdth\AzureServiceBusQueue\ServiceProvider;

abstract class TestCase extends BaseTestCase
{

    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $app['config']->set('queue.default', 'azureservicebus');
        $app['config']->set('queue.connections.azureservicebus', [
            'driver'       => 'azureservicebus',
            'endpoint'     => $_ENV['AZURESB_ENDPOINT'],
            'shared_access_key_name' => $_ENV['AZURESB_SHARED_ACCESS_KEY_NAME'],
            'shared_access_key' => $_ENV['AZURESB_SHARED_ACCESS_KEY'],
            'queue'        => $_ENV['AZURESB_QUEUE_NAME'],
        ]);
    }

    protected function connection(string $name = null): AzureQueue
    {
        return Queue::connection($name);
    }
}
