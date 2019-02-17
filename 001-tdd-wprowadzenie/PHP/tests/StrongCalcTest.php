<?php
declare(strict_types=1);

namespace App\Test;

use App\StrongCalc;
use PHPUnit\Framework\TestCase;
use \Exception;

/**
 * Class StrongCalcTest
 */
class StrongCalcTest extends TestCase
{

    /**
     * @var StrongCalc
     */
    private $strongCalc;

    /**
     *
     */
    public function setUp(): void
    {
        $this->strongCalc = new StrongCalc();

        parent::setUp();
    }

    /**
     * @throws Exception
     */
    public function testCalc(): void
    {
        $this->assertEquals(6, $this->strongCalc->calc(3));
    }

    /**
     * @throws Exception
     */
    public function testNegativeStrong(): void
    {
        $this->expectException(Exception::class);
        $this->strongCalc->calc(-3);
    }
}

