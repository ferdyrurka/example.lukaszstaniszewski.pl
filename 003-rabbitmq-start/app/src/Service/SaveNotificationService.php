<?php
declare(strict_types=1);

namespace App\Service;

use App\Factory\NotificationFactory;
use App\Repository\NotificationRepository;

/**
 * Class SaveNotificationService
 * @package App\Service
 */
class SaveNotificationService
{
    /**
     * @var NotificationRepository
     */
    private $notificationRepository;

    /**
     * @var NotificationFactory
     */
    private $notificationFactory;

    /**
     * SaveNotificationService constructor.
     * @param NotificationRepository $notificationRepository
     * @param NotificationFactory $notificationFactory
     */
    public function __construct(
        NotificationRepository $notificationRepository,
        NotificationFactory $notificationFactory
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->notificationFactory = $notificationFactory;
    }

    /**
     * @param array $arrayNotification
     * @throws \App\Exception\NotFullDataInFactoryException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(array $arrayNotification): void
    {
        $this->notificationRepository->save(
            $this->notificationFactory->buildNotification($arrayNotification)
        );
    }
}
