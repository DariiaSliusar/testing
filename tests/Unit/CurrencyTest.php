<?php

namespace Tests\Unit;

use App\Services\CurrencyService;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function testConvertUsdToEurSuccessful(): void
    {
        $result = (new CurrencyService())->convert(100, 'usd', 'eur');

        $this->assertEquals(85, $result);
    }

    public function testConvertUsdToGbpReturnZero(): void
    {
        $result = (new CurrencyService())->convert(100, 'usd', 'gbp');

        $this->assertEquals(0, $result);
    }
}
