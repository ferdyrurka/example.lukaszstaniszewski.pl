<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Notification;
use App\Exception\InvalidArgsException;
use App\Factory\NotificationFactory;
use App\Repository\NotificationRepository;
use App\Service\SaveNotificationService;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use \Mockery;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class SaveNotificationServiceTest
 * @package App\Tests\Service
 */
class SaveNotificationServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var NotificationFactory
     */
    private $notificationFactory;

    /**
     * @var NotificationRepository
     */
    private $notificationRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SaveNotificationService
     */
    private $saveNotificationService;

    /**
     * @var AMQPMessage
     */
    private $AMQPMessage;

    /**
     *
     */
    protected function setUp(): void
    {
        $this->AMQPMessage = Mockery::mock(AMQPMessage::class);
        $this->AMQPMessage->body = \json_encode(['data to create notification']);

        $this->notificationRepository = Mockery::mock(NotificationRepository::class);
        $this->notificationFactory = Mockery::mock(NotificationFactory::class);
        $this->validator = Mockery::mock(ValidatorInterface::class);

        $this->saveNotificationService = new SaveNotificationService(
            $this->notificationRepository,
            $this->notificationFactory,
            $this->validator
        );
    }

    /**
     * @test
     */
    public function saveOk(): void
    {
        $this->notificationRepository->shouldReceive('save')->withArgs([Notification::class])->once();

        $this->notificationFactory->shouldReceive('buildNotification')->once()
            ->andReturn(Mockery::mock(Notification::class))
        ;

        $this->validator->shouldReceive('validate')->once()->andReturn([]);

        $this->saveNotificationService->save($this->AMQPMessage);
    }

    /**
     * @test
     */
    public function validateFailed(): void
    {
        $this->notificationFactory->shouldReceive('buildNotification')->once()
            ->andReturn(Mockery::mock(Notification::class))
        ;

        $this->validator->shouldReceive('validate')->once()->andReturn(['Failed I search the invalid data']);

        $this->expectException(InvalidArgsException::class);
        $this->saveNotificationService->save($this->AMQPMessage);
    }
}
