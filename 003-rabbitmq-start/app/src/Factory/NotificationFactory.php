<?php
declare(strict_types=1);

namespace App\Factory;

use App\Composite\RabbitMQ\Component\AlertNotificationComponent;
use App\Composite\RabbitMQ\Component\NewMessageNotificationComponent;
use App\Composite\RabbitMQ\RabbitMQComponentAbstract;
use App\Entity\Notification;
use App\Exception\InvalidArgsException;
use App\Exception\NotFullDataInFactoryException;

/**
 * Class NotificationFactory
 * @package App\Factory
 */
class NotificationFactory
{
    /**
     * @param array $arrayNotification
     * @return Notification
     * @throws NotFullDataInFactoryException
     */
    public function buildNotification(array $arrayNotification): Notification
    {
        if (!isset($arrayNotification['message'], $arrayNotification['type'])) {
            throw new NotFullDataInFactoryException('Doesn\'t exist message or type in array notification');
        }

        $notification = new Notification();
        $notification->setMessage($arrayNotification['message']);
        $notification->setType($arrayNotification['type']);
    }

    /**
     * @param string $type
     * @param string $message
     * @return RabbitMQComponentAbstract
     * @throws InvalidArgsException
     */
    public function chooseNotificationComponent(string $type, string $message): RabbitMQComponentAbstract
    {
        switch ($type) {
            case 'alert':
                return new AlertNotificationComponent($message);
            case 'new_message':
                return new NewMessageNotificationComponent($message);
            default:
                throw new InvalidArgsException('Undefined type notification component');
        }
    }
}
