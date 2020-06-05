<?php


namespace Mrdth\AzureServiceBusQueue;


use Illuminate\Queue\Connectors\ConnectorInterface;
use WindowsAzure\Common\ServicesBuilder;

class AzureConnector implements ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @param array $config
     */
    public function connect(array $config)
    {
        $endpoint = $config['endpoint'];
        $sharedAccessName = $config['SharedAccessKeyName'];
        $sharedAccessKey = $config['SharedAccessKey'];
        $connectionString = "Endpoint=$endpoint;SharedAccessKeyName=$sharedAccessName;SharedAccessKey=$sharedAccessKey";
        $serviceBus = ServicesBuilder::getInstance()->createServiceBusService($connectionString);
        return new AzureQueue($serviceBus, $config['queue']);
    }
}
