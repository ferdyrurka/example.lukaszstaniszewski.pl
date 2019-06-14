<?php
declare(strict_types=1);

namespace App\Tests\Composite\RabbitMQ\Component;

use App\Composite\RabbitMQ\Component\AlertNotificationComponent;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use \Mockery;

class AlertNotificationComponentTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * @test
     * @runInSeparateProcess
     */
    public function runOk(): void
    {
        Mockery::getConfiguration()->setConstantsMap(
            [
                AMQPMessage::class => [
                    'DELIVERY_MODE_PERSISTENT' => 2
                ]
            ]
        );

        $amqpChannel = Mockery::mock(AMQPChannel::class);
        $amqpChannel->shouldReceive('queue_declare')->withArgs(
            [
                'alert_notification',
                false,
                true
            ]
        )->once();
        $amqpChannel->shouldReceive('basic_publish')->withArgs(
            [
                AMQPMessage::class,
                '',
                'alert_notification'
            ]
        )
            ->once()
        ;

        $amqpMessage = Mockery::mock('overload:' . AMQPMessage::class);
        $amqpMessage->shouldReceive('__construct')->withArgs(
            function (string $jsonData, array $conf): bool {
                $arrayData = \json_decode($jsonData, true);

                return !(empty($arrayData) || !isset($arrayData['message'], $conf['delivery_mode'])
                    || $conf['delivery_mode'] !== '2' || $arrayData['message'] !== 'Hello World'
                );
            }
        );

        $alertNotificationComponent = new AlertNotificationComponent('Hello World');
        $alertNotificationComponent->run($amqpChannel);
    }
}
