<?php
declare(strict_types=1);

namespace App\Test\Util;

use App\Util\Email;
use PHPUnit\Framework\TestCase;
use \Exception;

/**
 * Class EmailTest
 * @package App\Test\Util
 */
class EmailTest extends TestCase
{

    /**
     * @var
     */
    private $email;

    /**
     *
     */
    public function setUp(): void
    {
        $this->email = new Email();
    }

    /**
     * @test
     */
    public function send(): void
    {
        $this->assertTrue($this->email->send('kontakt@lukaszstaniszewski.pl'));
    }

    /**
     * @test
     */
    public function emailInvalid(): void
    {
        $this->expectException(Exception::class);
        $this->email->send('kontakt*lukaszstaniszewski.pl');
    }
}

