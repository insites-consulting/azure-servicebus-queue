<?php

namespace InsitesConsulting\AzureServiceBusQueue\Tests;

use InsitesConsulting\AzureServiceBusQueue\AzureQueue;

class ConnectionTest extends TestCase
{
    public function testConnection(): void
    {
        $queue = $this->connection();
        $this->assertInstanceOf(AzureQueue::class, $queue);
    }
}
