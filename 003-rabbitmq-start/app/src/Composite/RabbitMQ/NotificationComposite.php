<?php
declare(strict_types=1);

namespace App\Composite\RabbitMQ;

use App\Exception\CompositeStorageIsEmptyException;

/**
 * Class NotificationComposite
 * @package App\Composite\RabbitMQ
 */
class NotificationComposite extends RabbitMQCompositeAbstract
{
    /**
     * @throws CompositeStorageIsEmptyException
     */
    public function runComposite(): void
    {
        if ($this->components->count() <= 0) {
            throw new CompositeStorageIsEmptyException('Storage is empty in: ' . \get_class($this));
        }

        foreach ($this->components as $component) {
            $component->run($this->AMQPChannel);
        }

        $this->closeConnection();
    }

    /**
     * @param RabbitMQComponentAbstract $rabbitMQComponentAbstract
     * @return RabbitMQCompositeAbstract
     */
    public function add(RabbitMQComponentAbstract $rabbitMQComponentAbstract): RabbitMQCompositeAbstract
    {
        $this->components->attach($rabbitMQComponentAbstract);
        return $this;
    }

    /**
     * @param RabbitMQComponentAbstract $rabbitMQComponentAbstract
     * @return RabbitMQCompositeAbstract
     */
    public function remove(RabbitMQComponentAbstract $rabbitMQComponentAbstract): RabbitMQCompositeAbstract
    {
        $this->components->detach($rabbitMQComponentAbstract);
        return $this;
    }
}
