<?php


namespace Mrdth\AzureServiceBusQueue;

use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Queue;
use WindowsAzure\ServiceBus\Internal\IServiceBus;
use WindowsAzure\ServiceBus\Models\BrokeredMessage;
use WindowsAzure\ServiceBus\Models\ReceiveMessageOptions;

class AzureQueue extends Queue implements QueueContract
{
    /**
     * The Azure IServiceBus instance.
     *
     * @
     * var \WindowsAzure\ServiceBus\Internal\IServiceBus
     */
    protected $azure;

    /**
     * The name of the default topic.
     *
     * @var string
     */
    protected $default;

    /**
     * The options to set PeekAndLock.
     *
     * @var ReceiveMessageOptions
     */
    protected $receiveOptions;

    /**
     * Create a new Azure IQueue queue instance.
     *
     * @param IServiceBus $azure
     * @param string $default
     */
    public function __construct(IServiceBus $azure, $default)
    {
        $this->azure = $azure;
        $this->default = $default;
        $this->receiveOptions = new ReceiveMessageOptions();
        $this->receiveOptions->setPeekLock();
    }

    /**
     * @param string $queue
     * @param BrokeredMessage $message
     * @throws \Exception
     */
    protected function sendInternal(string $queue, BrokeredMessage $message): void
    {
        $this->azure->sendQueueMessage($queue, $message);
    }

    /**
     * @param string $queue
     * @param ReceiveMessageOptions $receiveOptions
     * @return BrokeredMessage|null
     * @throws \Exception
     */
    protected function receiveInternal(string $queue, ReceiveMessageOptions $receiveOptions): ?BrokeredMessage
    {
        return $this->azure->receiveQueueMessage($queue, $receiveOptions);
    }

    /**
     * @param null $queue
     * @return int
     */
    public function size($queue = null)
    {
        return 0;
    }

    /**
     * Push a new job onto the queue.
     *
     * @param string $job
     * @param mixed $data
     * @param string $queue
     * @throws \Exception
     */
    public function push($job, $data = '', $queue = null)
    {
        $this->pushRaw($this->createPayload($job, $data), $queue);
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param string $payload
     * @param string $queue
     * @param array $options
     *
     * @return mixed
     * @throws \Exception
     */
    public function pushRaw($payload, $queue = null, array $options = array())
    {
        if (is_object($payload)) {
            $payload = json_encode($payload);
        }
        $message = new BrokeredMessage($payload);
        $this->sendInternal($this->getQueue($queue), $message);
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param int $delay
     * @param string $job
     * @param mixed $data
     * @param string $queue
     * @throws \Exception
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        $payload = $this->createPayload($job, $data);
        $release = new \DateTime;
        $release->setTimezone(new \DateTimeZone('UTC'));
        $release->add(new \DateInterval('PT' . $delay . 'S'));
        $message = new BrokeredMessage($payload);
        $message->setScheduledEnqueueTimeUtc($release);
        $this->sendInternal($this->getQueue($queue), $message);
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param string $queue
     *
     * @return \Illuminate\Queue\Jobs\Job|null
     * @throws \Exception
     */
    public function pop($queue = null)
    {
        $popped = $this->receiveInternal($this->getQueue($queue), $this->receiveOptions);
        if (empty($popped)) {
            return null;
        }
        $rawMessage = $popped->getBody();
        return new AzureJob($this->container, $this->azure, $popped, $this->getQueue($queue), $rawMessage);
    }

    /**
     * Get the queue or return the default.
     *
     * @param string|null $queue
     *
     * @return string
     */
    public function getQueue($queue)
    {
        return $queue ?: $this->default;
    }
}
