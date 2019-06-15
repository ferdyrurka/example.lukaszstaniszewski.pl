<?php
declare(strict_types=1);

namespace App\Service;

use App\Composite\RabbitMQ\NotificationComposite;
use App\Composite\RabbitMQ\RabbitMQComponentAbstract;
use App\Exception\InvalidArgsException;
use App\Factory\NotificationFactory;
use App\Service\Validator\TypeNotificationValidator;

/**
 * Class AddNotificationToQueueService
 * @package App\Service
 */
class AddNotificationToQueueService
{
    /**
     * @var NotificationComposite
     */
    private $notificationComposite;

    /**
     * @var NotificationFactory
     */
    private $notificationFactory;

    /**
     * AddNotificationToQueueService constructor.
     * @param NotificationComposite $notificationComposite
     * @param NotificationFactory $notificationFactory
     */
    public function __construct(NotificationComposite $notificationComposite, NotificationFactory $notificationFactory)
    {
        $this->notificationComposite = $notificationComposite;
        $this->notificationFactory = $notificationFactory;
    }

    /**
     * @param array $notifications
     * @throws InvalidArgsException
     * @throws \App\Exception\CompositeStorageIsEmptyException
     */
    public function addToQueue(array $notifications): void
    {
        foreach ($notifications as $notification) {
            if ($this->validateNotification($notification)) {
                throw new InvalidArgsException('Invalid arguments in add to queue');
            }

            $component = $this->notificationFactory->chooseNotificationComponent(
                $notification['type'],
                $notification['message']
            );

            $this->notificationComposite->add($component);
        }

        $this->notificationComposite->runComposite();
    }

    /**
     * @param array $notification
     * @return bool
     */
    private function validateNotification(array $notification): bool
    {
        return (!isset($notification['message'], $notification['type']) ||
            !TypeNotificationValidator::validate($notification['type'])
        );
    }
}
