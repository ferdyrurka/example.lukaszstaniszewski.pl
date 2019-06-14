<?php
declare(strict_types=1);

namespace App\Composite\RabbitMQ;

use App\Service\RabbitMQConnectService;
use PhpAmqpLib\Channel\AMQPChannel;
use \SplObjectStorage;

/**
 * Class RabbitMQCompositeAbstract
 * @package App\Composite\RabbitMQ
 */
abstract class RabbitMQCompositeAbstract extends RabbitMQComponentAbstract
{
    /**
     * @var AMQPChannel
     */
    protected $AMQPChannel;

    /**
     * @var SplObjectStorage
     */
    protected $components;

    /**
     * @var RabbitMQConnectService
     */
    private $rabbitMQConnectService;

    /**
     * RabbitMQComponentAbstract constructor.
     * @param RabbitMQConnectService $rabbitMQConnectService
     */
    public function __construct(RabbitMQConnectService $rabbitMQConnectService)
    {
        $this->rabbitMQConnectService = $rabbitMQConnectService;
        $this->AMQPChannel = $this->rabbitMQConnectService->getChannel();
        $this->components = new SplObjectStorage();
    }

    /**
     * @return bool
     */
    public function isComposite(): bool
    {
        return true;
    }

    /**
     * @param AMQPChannel $AMQPChannel
     */
    public function run(AMQPChannel $AMQPChannel): void
    {
        // Do nothing
    }

    /**
     *
     */
    abstract public function runComposite(): void;

    /**
     * @param RabbitMQComponentAbstract $rabbitMQComponentAbstract
     * @return RabbitMQCompositeAbstract
     */
    abstract public function add(RabbitMQComponentAbstract $rabbitMQComponentAbstract): RabbitMQCompositeAbstract;

    /**
     * @param RabbitMQComponentAbstract $rabbitMQComponentAbstract
     * @return RabbitMQCompositeAbstract
     */
    abstract public function remove(RabbitMQComponentAbstract $rabbitMQComponentAbstract): RabbitMQCompositeAbstract;

    /**
     * @throws \Exception
     */
    protected function closeConnection(): void
    {
        $this->rabbitMQConnectService->closeConnection($this->AMQPChannel);
    }
}
