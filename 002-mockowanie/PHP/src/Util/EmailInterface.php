<?php
declare(strict_types=1);

namespace App\Util;

/**
 * Interface EmailInterface
 * @package App\Util
 */
interface EmailInterface
{
    /**
     * @param string $email
     * @return bool
     */
    public function send(string $email): bool;
}
