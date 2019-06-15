<?php
declare(strict_types=1);

namespace App\Tests\Console\RabbitMQ;

use App\Console\RabbitMQ\NotificationWorkerConsole;
use App\Exception\InvalidArgsException;
use App\Service\RabbitMQConnectService;
use App\Service\SaveNotificationService;
use PhpAmqpLib\Channel\AMQPChannel;
use PHPUnit\Framework\TestCase;
use \Mockery;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \Exception;

/**
 * Class NotificationWorkerConsoleTest
 * @package App\Tests\Console\RabbitMQ
 */
class NotificationWorkerConsoleTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * @var AMQPChannel
     */
    private $AMQPChannel;

    /**
     * @var SaveNotificationService
     */
    private $saveNotificationService;

    /**
     * @var NotificationWorkerConsole
     */
    private $notificationWorkerConsole;

    /**
     *
     */
    protected function setUp(): void
    {
        $this->AMQPChannel = Mockery::mock(AMQPChannel::class);
        $this->saveNotificationService = Mockery::mock(SaveNotificationService::class);

        $rabbitMQConnectService = Mockery::mock(RabbitMQConnectService::class);
        $rabbitMQConnectService->shouldReceive('getChannel')->once()->andReturn(
            $this->AMQPChannel
        );

        $this->notificationWorkerConsole = new NotificationWorkerConsole(
            $rabbitMQConnectService,
            $this->saveNotificationService
        );
    }

    /**
     * @throws InvalidArgsException
     * @test
     */
    public function undefinedType(): void
    {
        $input = Mockery::mock(InputInterface::class);
        $input->shouldReceive('getArgument')->withArgs(['type'])->andReturn('alert_failed');

        $this->expectException(InvalidArgsException::class);
        $this->notificationWorkerConsole->execute($input, Mockery::mock(OutputInterface::class));
    }

    /**
     * @test
     */
    public function workerWorkOk(): void
    {
        $input = Mockery::mock(InputInterface::class);
        $input->shouldReceive('getArgument')->withArgs(
            function (string $name) : bool {
                return !($name !== 'type' && $name !== 'durable');
            }
        )
            ->andReturn('alert', 'false')
        ;

        $this->AMQPChannel->shouldReceive('queue_declare')->withArgs(['alert_notification', false, false]);
        $this->AMQPChannel->shouldReceive('basic_consume')->withArgs(
            function (
                string $queue,
                string $consumerTag,
                bool $noLocal,
                bool $noAck,
                bool $exclusive,
                bool $noWait,
                array $callable
            ): bool {
                return !($queue !== 'alert_notification' || $noLocal || !$noAck || $exclusive || $noWait ||
                    !empty($consumerTag) || !$callable[0] instanceof SaveNotificationService || $callable[1] !== 'save'
                );
            }
        )->once()
        ;

        $output = Mockery::mock(OutputInterface::class);
        $output->shouldReceive('writeln')->once();

        $this->notificationWorkerConsole->execute($input, $output);
    }
}
