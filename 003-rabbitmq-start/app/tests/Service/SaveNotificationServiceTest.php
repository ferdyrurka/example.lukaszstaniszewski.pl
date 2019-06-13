<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Notification;
use App\Factory\NotificationFactory;
use App\Repository\NotificationRepository;
use App\Service\SaveNotificationService;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use \Mockery;

/**
 * Class SaveNotificationServiceTest
 * @package App\Tests\Service
 */
class SaveNotificationServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @throws \App\Exception\NotFullDataInFactoryException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @test
     */
    public function saveOk(): void
    {
        $notificationRepository = Mockery::mock(NotificationRepository::class);
        $notificationRepository->shouldReceive('save')->withArgs([Notification::class])->once();

        $notificationFactory = Mockery::mock(NotificationFactory::class);
        $notificationFactory->shouldReceive('buildNotification')->once()
            ->andReturn(Mockery::mock(Notification::class))
        ;

        $saveNotificationService = new SaveNotificationService($notificationRepository, $notificationFactory);
        $saveNotificationService->save(['data to create notification']);
    }
}
