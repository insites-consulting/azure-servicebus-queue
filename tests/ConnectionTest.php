<?php

namespace Mrdth\AzureServiceBusQueue\Tests;

use Mrdth\AzureServiceBusQueue\AzureQueue;

class ConnectionTest extends TestCase
{
    public function testConnection(): void
    {
        $queue = $this->connection();
        $this->assertInstanceOf(AzureQueue::class, $queue);
    }
}
