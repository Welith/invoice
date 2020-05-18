<?php

namespace tests;

require '../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Calculator\Calculator;
use Helpers\DocumentHelper;
use Helpers\StringHelper;

/**
 * Class StringHelperTester
 * @package tests
 */
class StringHelperTester extends TestCase
{
    /* @test */
    public function testFullInput()
    {
        $data = ['script', __DIR__ . "\data.csv", 'EUR:1,USD:0.987,GBP:0.878', 'GBP'];
        $this->assertTrue(StringHelper::isFullInput($data));
    }

    /* @test */
    public function testNotFullInput()
    {
        $this->expectException(\Exception::class);
        $data = ['script', __DIR__ . "\data.csv", 'EUR:1,USD:0.987,GBP:0.878'];
        StringHelper::isFullInput($data);
    }

    /* @test */
    public function testTooManyInputs()
    {
        $this->expectException(\Exception::class);
        $data = ['script', __DIR__ . "\data.csv", 'EUR:1,USD:0.987,GBP:0.878', 'GBP', '--vat=122112', 'exceed'];
        StringHelper::isFullInput($data);
    }

    /* @test */
    public function testParseExchangeCorrect()
    {
        $data = 'EUR:1,USD:0.987,GBP:0.878';
        $this->assertEquals([
            'EUR' => '1',
            'USD' => '0.987',
            'GBP' => '0.878'
        ], StringHelper::parseExchangeString($data));
    }
    /* @test */
    public function testParseExchangeIncorrect()
    {
        $this->expectException(\Exception::class);
        $data = 'EUR:1,USD:0.987';
        StringHelper::parseExchangeString($data);
    }
    /* @test */
    public function testParseExchangeInvalidCurr()
    {
        $this->expectException(\Exception::class);
        $data = 'EUR:1,USD:0.987,GBP:0.878,BGN:23';
        StringHelper::parseExchangeString($data);
    }
}
