<?php

namespace InsitesConsulting\AzureServiceBusQueue;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $manager = $this->app['queue'];

        $manager->addConnector('azureservicebus', function () {
            return new AzureConnector();
        });
    }
}
