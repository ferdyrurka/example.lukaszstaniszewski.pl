<?php
declare(strict_types=1);

namespace App\Tests\Composite\RabbitMQ;

use App\Composite\RabbitMQ\NotificationComposite;
use App\Composite\RabbitMQ\RabbitMQComponentAbstract;
use App\Exception\CompositeStorageIsEmptyException;
use App\Service\RabbitMQConnectService;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PhpAmqpLib\Channel\AMQPChannel;
use PHPUnit\Framework\TestCase;
use \SplObjectStorage;
use \Mockery;

/**
 * Class NotificationCompositeTest
 * @package App\Tests\Composite\RabbitMQ
 */
class NotificationCompositeTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var SplObjectStorage
     */
    private $sqplObjectStorage;

    /**
     * @var NotificationComposite
     */
    private $notificationComposite;

    /**
     *
     */
    public function setUp(): void
    {
        $this->sqplObjectStorage = Mockery::mock(SplObjectStorage::class);
        $rabbitMQConnectService = Mockery::mock(RabbitMQConnectService::class);
        $rabbitMQConnectService->shouldReceive('getChannel')->once()
            ->andReturn(Mockery::mock(AMQPChannel::class))
        ;

        $this->notificationComposite = new NotificationComposite($rabbitMQConnectService, $this->sqplObjectStorage);
    }

    /**
     * @test
     */
    public function addOk(): void
    {
        $this->sqplObjectStorage->shouldReceive('attach')->withArgs([RabbitMQComponentAbstract::class])->once();
        $this->notificationComposite->add(Mockery::mock(RabbitMQComponentAbstract::class));
    }

    /**
     * @test
     */
    public function removeOk(): void
    {
        $this->sqplObjectStorage->shouldReceive('detach')->withArgs([RabbitMQComponentAbstract::class])->once();
        $this->notificationComposite->remove(Mockery::mock(RabbitMQComponentAbstract::class));
    }

    /**
     * @throws CompositeStorageIsEmptyException
     * @test
     */
    public function storageIsEmpty(): void
    {
        $this->sqplObjectStorage->shouldReceive('count')->once()->andReturn(0);

        $this->expectException(CompositeStorageIsEmptyException::class);
        $this->notificationComposite->runComposite();
    }

    /**
     * @throws CompositeStorageIsEmptyException
     * @test
     */
    public function runComposite(): void
    {
        $component = Mockery::mock(RabbitMQComponentAbstract::class);
        $component->shouldReceive('run')->once()->withArgs([AMQPChannel::class]);

        $this->sqplObjectStorage->shouldReceive('count')->once()->andReturn(1);
        $this->sqplObjectStorage->shouldReceive('valid')->twice()->andReturn(true, false);
        $this->sqplObjectStorage->shouldReceive('current')->once()->andReturn($component);
        $this->sqplObjectStorage->shouldReceive('next')->once();
        $this->sqplObjectStorage->shouldReceive('rewind')->once();

        $this->notificationComposite->runComposite();
    }
}
