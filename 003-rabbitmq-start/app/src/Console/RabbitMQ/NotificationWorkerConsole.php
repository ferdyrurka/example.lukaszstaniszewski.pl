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

        $this->AMQPChannel->queue_declare(
            $type . '_notification',
            false,
            (bool) ($input->getArgument('durable') === 'true')
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

        $output->writeln(ucfirst($type) . ' notification worker ready to working');

        while (\count($this->AMQPChannel->callbacks)) {
            try {
                $this->AMQPChannel->wait();
                $output->writeln('I\'m added notification to database!');
            } catch (\Exception $exception) {
                $output->writeln('Exception message: ' . $exception->getMessage());
            }
        }
    }
}
