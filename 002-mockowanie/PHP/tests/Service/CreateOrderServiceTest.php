<?php
declare(strict_types=1);

namespace App\Test\Service;

use App\Service\CreateOrderService;
use App\Util\EmailInterface;
use PHPUnit\Framework\TestCase;
use \Mockery;
use \Exception;

/**
 * Class CreateOrderServiceTest
 * @package App\Test\Service
 */
class CreateOrderServiceTest extends TestCase
{
    //Trait for Integration with PHPUnit, second method is use listener in PHPUnit configuration
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * @var
     */
    private $email;

    /**
     * @var CreateOrderService
     */
    private $createOrderService;

    /**
     *
     */
    public function setUp(): void
    {
        $this->email = Mockery::mock(EmailInterface::class);
        $this->createOrderService = new CreateOrderService($this->email);
    }

    /**
     * @throws Exception
     * @test
     */
    public function createOrder(): void
    {
        $this->email->shouldReceive('send')->once()->andReturnTrue();
        $this->createOrderService->createOrder('kontakt@lukaszstaniszewski.pl');
    }

    /**
     * @throws Exception
     * @test
     */
    public function notSendEmail(): void
    {
        $this->email->shouldReceive('send')->once()->andReturnFalse();

        $this->expectException(Exception::class);
        $this->createOrderService->createOrder('kontaktlukaszstaniszewski.pl');
    }
}
