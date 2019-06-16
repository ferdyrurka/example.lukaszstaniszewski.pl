<?php
declare(strict_types=1);

namespace App\Composite\RabbitMQ\Component;

use App\Composite\RabbitMQ\RabbitMQComponentAbstract;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class AlertNotificationComponent
 * @package App\Composite\RabbitMQ\Component
 */
class AlertNotificationComponent extends RabbitMQComponentAbstract
{
    /**
     * @var string
     */
    private $message;

    /**
     * AlertNotificationComponent constructor.
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
        $message = new AMQPMessage(
            \json_encode([
                'message' => $this->message,
                'type' => 'alert'
            ]),
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );

        $AMQPChannel->basic_publish($message, '', 'alert_notification');
    }
}
