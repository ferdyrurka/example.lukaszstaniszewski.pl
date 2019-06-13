<?php
declare(strict_types=1);

namespace App\Service;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class RabbitMQConnectService
 * @package App\Service
 */
class RabbitMQConnectService
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * RabbitMQConnectService constructor.
     * @param AMQPStreamConnection $connection
     */
    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return AMQPChannel
     */
    public function getChannel(): AMQPChannel
    {
        if (!$this->connection->isConnected()) {
            $this->connection->reconnect();
        }

        return $this->connection->channel();
    }

    /**
     * @param AMQPChannel $AMQPChannel
     * @throws \Exception
     */
    public function closeConnection(AMQPChannel $AMQPChannel): void
    {
        $AMQPChannel->close();
        $this->connection->close();
    }
}
