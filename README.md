Windows Azure Servicebus Queue driver for Laravel, with MassTransit compatible payloads
=======================================================================================
### Overview
The library provides support for Service Bus queues. The package should be auto discovered on Laravel > 5.6

#### Installation

Require this package with composer:

	composer require insites-consulting/azure-service-bus-queue

Run composer update!

After composer update has, finished you need to add the following to the `connection` array in `app/config/queue.php`, and fill out your own connection data from the Azure Management portal:

	'azureservicebus' => [
        'driver'       => 'azureservicebus',
        'endpoint'     => 'https://*.servicebus.windows.net',
        'shared_access_key_name' => '',
        'shared_access_key' => 'primary key',
        'queue'        => '<queue name>',
    ]

#### Usage
Once you completed the configuration you can use Laravel Queue API. If you do not know how to use Queue API, please refer to the official Laravel [documentation](http://laravel.com/docs/queues).

From laravel Queue documentation, something like this should work:
```php
        $payload = new \stdClass();
        $payload->id = 1;
        $payload->name = 'hello world';
        ProcessPodcast::dispatch($payload)->onConnection('azureservicebus')->onQueue('queue-name');
```
artisan worker should be started as per Laravel's official documentation:

```shell
php artisan queue:listen azureservicebus --queue=queue-name
```


#### Attribution
Inspired by https://github.com/goavega-software/laravel-azure-servicebus-topic & https://github.com/pawprintdigital/laravel-queue-raw-sqs
