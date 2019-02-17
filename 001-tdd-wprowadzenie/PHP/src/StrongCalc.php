<?php
declare(strict_types=1);

namespace App;

use \Exception;

/**
 * Class StrongCalc
 * @package App
 */
class StrongCalc
{
    /**
     * @param int $strong
     * @return int
     * @throws Exception
     */
    public function calc(int $strong): int
    {
        if ($strong <= 0) {
            throw new Exception("Strong can't be negative!");
        }

        $strongResult = 1;

        for ($i = 1; $i <= $strong; ++$i) {
            $strongResult *= $i;
        }

        return $strongResult;
    }
}
