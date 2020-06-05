Windows Azure Servicebus Queue driver for Laravel, with MassTransit compatible payloads
=======================================================================================
### Overview
The library provides support for Service Bus queues. Job payloads are created to provide interoperability with MassTransit. The package should be auto discovered on Laravel > 5.6

#### Installation

Require this package in your `composer.json`:

	"mrdth/azure-service-bus-queue": "<<version>>"

Run composer update!

After composer update is finished you need to add the following to the `connection` array in `app/config/queue.php`, and fill out your own connection data from the Azure Management portal:

	'azureservicebus' => [
        'driver'       => 'azureservicebus',
        'endpoint'     => 'https://*.servicebus.windows.net',
        'SharedAccessKeyName' => '',
        'SharedAccessKey' => 'primary key',
        'queue'        => '<topic name>',
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

### Version compatiblity
* Use version 2.x if you are on Laravel 5.5
* Use version 5.x if you are on Laravel 5.6-5.8
* Support for laravel 6.x is not tested (PRs welcome)
#### Contribution

You can contribute to this package by opening issues/pr's. Enjoy!

#### Attribution
Inspired by https://github.com/goavega-software/laravel-azure-servicebus-topic & https://github.com/pawprintdigital/laravel-queue-raw-sqs
