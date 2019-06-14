<?php
declare(strict_types=1);

namespace App\Composite\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;

/**
 * Class RabbitMQComponentAbstract
 * @package App\Composite\RabbitMQ
 */
abstract class RabbitMQComponentAbstract
{
    /**
     * @param AMQPChannel $AMQPChannel
     */
    abstract public function run(AMQPChannel $AMQPChannel): void;
}
