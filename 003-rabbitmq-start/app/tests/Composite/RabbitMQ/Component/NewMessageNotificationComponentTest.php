<?php
declare(strict_types=1);

namespace App\Tests\Composite\RabbitMQ\Component;

use App\Composite\RabbitMQ\Component\NewMessageNotificationComponent;
use Mockery;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;

/**
 * Class NewMessageNotificationComponentTest
 * @package App\Tests\Composite\RabbitMQ\Component
 */
class NewMessageNotificationComponentTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * @test
     * @runInSeparateProcess
     */
    public function runOk(): void
    {
        $amqpChannel = Mockery::mock(AMQPChannel::class);
        $amqpChannel->shouldReceive('basic_publish')->withArgs(
            [
                AMQPMessage::class,
                '',
                'new_message_notification'
            ]
        )
            ->once()
        ;

        $amqpMessage = Mockery::mock('overload:' . AMQPMessage::class);
        $amqpMessage->shouldReceive('__construct')->withArgs(
            function (string $jsonData): bool {
                $arrayData = \json_decode($jsonData, true);

                return !(empty($arrayData) || !isset($arrayData['message']) ||
                    $arrayData['message'] !== 'Hello World'
                );
            }
        );

        $newMessageNotificationComponent = new NewMessageNotificationComponent('Hello World');
        $newMessageNotificationComponent->run($amqpChannel);
    }
}
