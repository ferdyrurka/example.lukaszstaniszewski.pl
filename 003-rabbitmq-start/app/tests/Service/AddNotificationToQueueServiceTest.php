<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Composite\RabbitMQ\Component\AlertNotificationComponent;
use App\Composite\RabbitMQ\Component\NewMessageNotificationComponent;
use App\Composite\RabbitMQ\NotificationComposite;
use App\Exception\InvalidArgsException;
use App\Factory\NotificationFactory;
use App\Service\AddNotificationToQueueService;
use PHPUnit\Framework\TestCase;
use \Mockery;

/**
 * Class AddNotificationToQueueServiceTest
 * @package App\Tests\Service
 */
class AddNotificationToQueueServiceTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * @var AddNotificationToQueueService
     */
    private $addNotificationToQueueService;

    /**
     * @var NotificationComposite
     */
    private $notificationComposite;

    /**
     * @var NotificationFactory
     */
    private $notificationFactory;

    /**
     *
     */
    public function setUp(): void
    {
        $this->notificationComposite = Mockery::mock(NotificationComposite::class);
        $this->notificationFactory = Mockery::mock(NotificationFactory::class);
        $this->addNotificationToQueueService = new AddNotificationToQueueService(
            $this->notificationComposite,
            $this->notificationFactory
        );
    }


    /**
     * @param array $notifications
     * @throws InvalidArgsException
     * @throws \App\Exception\CompositeStorageIsEmptyException
     * @test
     * @dataProvider getInvalidNotification
     */
    public function addToQueueInvalidArgsInNotifications(array $notifications): void
    {
        $this->expectException(InvalidArgsException::class);
        $this->addNotificationToQueueService->addToQueue($notifications);
    }

    /**
     * @return array
     */
    public function getInvalidNotification(): array
    {
        return [
            [
                'notifications' => [
                    ['type' => 'alert']
                ]
            ],
            [
                'notifications' => [
                    ['message' => 'Hello World']
                ]
            ]
        ];
    }

    /**
     * @throws InvalidArgsException
     * @throws \App\Exception\CompositeStorageIsEmptyException
     * @test
     */
    public function addToQueueOk(): void
    {
        $this->notificationComposite->shouldReceive('add')->withArgs(
            function ($component): bool {
                return (!$component instanceof AlertNotificationComponent ||
                !$component instanceof NewMessageNotificationComponent
                );
            }
        )->twice();
        $this->notificationComposite->shouldReceive('runComposite')->once();
        $this->notificationFactory->shouldReceive('chooseNotificationComponent')->twice()
            ->andReturn(
                Mockery::mock(AlertNotificationComponent::class),
                Mockery::mock(NewMessageNotificationComponent::class)
            )
        ;

        $this->addNotificationToQueueService->addToQueue([
            ['message' => 'Hello', 'type' => 'alert'],
            ['message' => 'World', 'type' => 'new_message'],
        ]);
    }
}
