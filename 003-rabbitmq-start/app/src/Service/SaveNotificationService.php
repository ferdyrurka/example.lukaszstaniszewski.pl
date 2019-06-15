<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\InvalidArgsException;
use App\Factory\NotificationFactory;
use App\Repository\NotificationRepository;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * SaveNotificationService constructor.
     * @param NotificationRepository $notificationRepository
     * @param NotificationFactory $notificationFactory
     * @param ValidatorInterface $validator
     */
    public function __construct(
        NotificationRepository $notificationRepository,
        NotificationFactory $notificationFactory,
        ValidatorInterface $validator
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->notificationFactory = $notificationFactory;
        $this->validator = $validator;
    }

    /**
     * @param AMQPMessage $jsonNotification
     * @throws InvalidArgsException
     * @throws \App\Exception\NotFullDataInFactoryException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(AMQPMessage $jsonNotification): void
    {
        $arrayNotification = \json_decode($jsonNotification->body, true);

        $notification = $this->notificationFactory->buildNotification($arrayNotification);

        if (\count($this->validator->validate($notification)) > 0) {
            throw new InvalidArgsException('Invalid arguments in notification entity');
        }

        $this->notificationRepository->save($notification);
    }
}
