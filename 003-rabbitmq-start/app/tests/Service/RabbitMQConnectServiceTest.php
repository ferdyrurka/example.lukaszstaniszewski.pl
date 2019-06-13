<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\RabbitMQConnectService;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PHPUnit\Framework\TestCase;
use \Mockery;

/**
 * Class RabbitMQConnectServiceTest
 * @package App\Tests\Service
 */
class RabbitMQConnectServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var AMQPStreamConnection
     */
    private $AMQPStreamConnection;

    /**
     * @var RabbitMQConnectService
     */
    private $rabbitMQConnectService;

    /**
     *
     */
    public function setUp(): void
    {
        $this->AMQPStreamConnection = Mockery::mock(AMQPStreamConnection::class);
        $this->rabbitMQConnectService = new RabbitMQConnectService($this->AMQPStreamConnection);
    }

    /**
     * @test
     */
    public function getChannelConnectedTrue(): void
    {
        $this->AMQPStreamConnection->shouldReceive('isConnected')->once()->andReturnTrue();
        $this->AMQPStreamConnection->shouldReceive('channel')->once()->andReturn(Mockery::mock(AMQPChannel::class));

        $this->rabbitMQConnectService->getChannel();
    }

    /**
     * @test
     */
    public function getChannelConnectedFalse(): void
    {
        $this->AMQPStreamConnection->shouldReceive('isConnected')->once()->andReturnFalse();
        $this->AMQPStreamConnection->shouldReceive('reconnect')->once();
        $this->AMQPStreamConnection->shouldReceive('channel')->once()->andReturn(Mockery::mock(AMQPChannel::class));

        $this->rabbitMQConnectService->getChannel();
    }

    /**
     * @test
     */
    public function closeConnection(): void
    {
        $AMQPChannel = Mockery::mock(AMQPChannel::class);
        $AMQPChannel->shouldReceive('close')->once();

        $this->AMQPStreamConnection->shouldReceive('close')->once();

        $this->rabbitMQConnectService->closeConnection($AMQPChannel);
    }
}
