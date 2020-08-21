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
        $shared_access_key_name = $config['shared_access_key_name'];
        $shared_access_key= $config['shared_access_key'];
        $connectionString = "Endpoint=$endpoint;SharedAccessKeyName=$shared_access_key_name;SharedAccessKey=$shared_access_key";
        $serviceBus = ServicesBuilder::getInstance()->createServiceBusService($connectionString);
        return new AzureQueue($serviceBus, $config['queue']);
    }
}
