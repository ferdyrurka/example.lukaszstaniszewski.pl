<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\Notification;
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
}
