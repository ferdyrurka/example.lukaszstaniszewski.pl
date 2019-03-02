<?php
declare(strict_types=1);

namespace App\Util;

use \Exception;

/**
 * Class Email
 * @package App\Util
 */
class Email implements EmailInterface
{
    /**
     * @param string $email
     * @return bool
     * @throws Exception
     */
    public function send(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email is not valid');
        }

        //Send email

        return true;
    }
}
