<?php
declare(strict_types=1);

namespace App\Console\RabbitMQ;

use App\Exception\InvalidArgsException;
use App\Service\RabbitMQConnectService;
use App\Service\SaveNotificationService;
use App\Service\Validator\TypeNotificationValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AlertNotificationWorkerConsole
 * @package App\Console\RabbitMQ
 */
class NotificationWorkerConsole extends Command
{
    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    private $AMQPChannel;

    /**
     * @var SaveNotificationService
     */
    private $saveNotificationService;

    /**
     * NotificationWorkerConsole constructor.
     * @param RabbitMQConnectService $rabbitMQConnectService
     * @param SaveNotificationService $saveNotificationService
     */
    public function __construct(
        RabbitMQConnectService $rabbitMQConnectService,
        SaveNotificationService $saveNotificationService
    ) {
        $this->AMQPChannel = $rabbitMQConnectService->getChannel();
        $this->saveNotificationService = $saveNotificationService;
        parent::__construct();
    }

    /**
     * @codeCoverageIgnore
     */
    public function configure(): void
    {
        $this->setName('rabbitMQ:notification');
        $this->addArgument('type', InputArgument::REQUIRED, 'alert or new_message');
        $this->addArgument('durable', InputArgument::REQUIRED, 'alert or new_message');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws InvalidArgsException
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $type = $input->getArgument('type');
        if (!TypeNotificationValidator::validate($type)) {
            throw new InvalidArgsException('Undefined type');
        }

        $output->writeln(ucfirst($type) . ' notification worker ready to working');
        $durable = $this->getDurable($input->getArgument('durable'));
        $this->consumeChannel($type, $durable);

        while (\count($this->AMQPChannel->callbacks)) {
            try {
                $this->AMQPChannel->wait();
                $output->writeln('I\'m added notification to database!');
            } catch (\Exception $exception) {
                $output->writeln('Exception message: ' . $exception->getMessage());
            }
        }
    }

    /**
     * @param string $durableArgs
     * @return bool
     * @throws InvalidArgsException
     */
    private function getDurable(string $durableArgs): bool
    {
        if ($durableArgs === 'true') {
            return true;
        } elseif ($durableArgs === 'false') {
            return false;
        }

        throw new InvalidArgsException('Undefined durable');
    }

    /**
     * @param string $type
     * @param bool $durable
     */
    private function consumeChannel(string $type, bool $durable): void
    {
        $this->AMQPChannel->queue_declare(
            $type . '_notification',
            false,
            $durable
        );
        $this->AMQPChannel->basic_consume(
            $type . '_notification',
            '',
            false,
            true,
            false,
            false,
            [$this->saveNotificationService, 'save']
        );
    }
}
