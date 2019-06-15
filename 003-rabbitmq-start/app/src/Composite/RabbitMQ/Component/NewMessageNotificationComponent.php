<?php
declare(strict_types=1);

namespace App\Composite\RabbitMQ\Component;

use App\Composite\RabbitMQ\RabbitMQComponentAbstract;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class NewMessageNotificationComponent
 * @package App\Composite\RabbitMQ\Component
 */
class NewMessageNotificationComponent extends RabbitMQComponentAbstract
{
    /**
     * @var string
     */
    private $message;

    /**
     * NewMessageNotificationComponent constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @param AMQPChannel $AMQPChannel
     */
    public function run(AMQPChannel $AMQPChannel): void
    {
        $AMQPChannel->queue_declare('new_message_notification');

        $message = new AMQPMessage(
            \json_encode([
                'message' => $this->message,
                'type' => 'new_message'
            ])
        );

        $AMQPChannel->basic_publish($message, '', 'new_message_notification');
    }
}
