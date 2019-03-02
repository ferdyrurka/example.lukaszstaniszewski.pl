<?php
declare(strict_types=1);

namespace App\Service;

use App\Util\EmailInterface;
use \Exception;

/**
 * Class CreateOrderService
 * @package App\Handler
 */
class CreateOrderService
{
    /**
     * @var EmailInterface
     */
    private $email;

    /**
     * CreateOrderService constructor.
     * @param EmailInterface $email
     */
    public function __construct(EmailInterface $email)
    {
        $this->email = $email;
    }


    /**
     * @param string $email
     * @throws Exception
     */
    public function createOrder(string $email): void
    {
        if (!$this->email->send($email)) {
            throw new Exception('Email not send');
        }

        // Other business logic
    }
}
