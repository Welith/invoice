<?php

namespace tests;

require '../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Calculator\Calculator;
use Helpers\DocumentHelper;
use Helpers\StringHelper;

/**
 * Class CalculatorTester
 * @package tests
 */
class CalculatorTester extends TestCase
{
    /** @test */
    public function testCalcWithCorrectDataNoOpt()
    {
        $file = __DIR__ . "\data.csv";
        $exchange_arr = StringHelper::parseExchangeString('EUR:1,USD:0.987,GBP:0.878');
        $file_data = DocumentHelper::parseCSV($file);
        $output_currency = 'GBP';
        $opt_param = null;
        $calculator = new Calculator($file_data, $exchange_arr, $output_currency, $opt_param);
        $test_case = $calculator->calculateTotalInvoice();
        $this->assertEquals(("Customer Vendor 1 Total: 1722.83 GBP.\n" . "Customer Vendor 2 Total: 612.29 GBP.\n" . "Customer Vendor 3 Total: 1387.8 GBP.\n"),
            $calculator->displayResult($test_case, $opt_param));
    }

    /** @test */
    public function testCalcWithCorrectDataWithOpt()
    {
        $file = __DIR__ . "\data.csv";
        $exchange_arr = StringHelper::parseExchangeString('EUR:1,USD:0.987,GBP:0.878');
        $file_data = DocumentHelper::parseCSV($file);
        $output_currency = 'GBP';
        $opt_param = '123456789';
        $calculator = new Calculator($file_data, $exchange_arr, $output_currency, $opt_param);
        $test_case = $calculator->calculateTotalInvoice();
        $this->assertEquals(('Customer Vendor 1 Total: 1722.83 GBP.'),
            $calculator->displayResult($test_case, $opt_param));
    }

    /** @test */
    public function testCalcWithCSVMissingParent()
    {
        $this->expectException(\Exception::class);
        $file = __DIR__ . "\dataMissingParent.csv";
        $exchange_arr = StringHelper::parseExchangeString('EUR:1,USD:0.987,GBP:0.878');
        $file_data = DocumentHelper::parseCSV($file);
        $output_currency = 'GBP';
        $opt_param = '123456789';
        $calculator = new Calculator($file_data, $exchange_arr, $output_currency, $opt_param);
        $test_case = $calculator->calculateTotalInvoice();
        $this->assertEquals(('Customer Vendor 1 Total: 1722.83 GBP.'),
            $calculator->displayResult($test_case, $opt_param));
    }

    /** @test */
    public function testCalcWithMissingCSV()
    {
        $this->expectException(\Exception::class);
        $file = __DIR__ . "\data123.csv";
        $exchange_arr = StringHelper::parseExchangeString('EUR:1,USD:0.987,GBP:0.878');
        $file_data = DocumentHelper::parseCSV($file);
        $output_currency = 'GBP';
        $opt_param = '123456789';
        $calculator = new Calculator($file_data, $exchange_arr, $output_currency, $opt_param);
        $test_case = $calculator->calculateTotalInvoice();
        $this->assertEquals(('Customer Vendor 1 Total: 1722.83 GBP.'),
            $calculator->displayResult($test_case, $opt_param));
    }
    /** @test */
    public function testCalcWithCreditHigherThanInvoice()
    {
        $this->expectException(\Exception::class);
        $file = __DIR__ . "\dataCredit.csv";
        $exchange_arr = StringHelper::parseExchangeString('EUR:1,USD:0.987,GBP:0.878');
        $file_data = DocumentHelper::parseCSV($file);
        $output_currency = 'GBP';
        $opt_param = '123456789';
        $calculator = new Calculator($file_data, $exchange_arr, $output_currency, $opt_param);
        $test_case = $calculator->calculateTotalInvoice();
        $this->assertEquals(('Customer Vendor 1 Total: 1722.83 GBP.'),
            $calculator->displayResult($test_case, $opt_param));
    }
}

