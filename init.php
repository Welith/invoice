<?php
require 'vendor/autoload.php';

use Helpers\DocumentHelper;
use Helpers\StringHelper;
use Helpers\CurrencyHelper;
use Calculator\Calculator;

try {
    // Initialize the input stream params
    $customer_vat = null;
    if (StringHelper::isFullInput($argv)) {
        $file_data = DocumentHelper::parseCSV($argv[1]);
        $currency_array = StringHelper::parseExchangeString($argv[2]);;
        $output_currency = CurrencyHelper::isAllowedCurrency($argv[3]) ? $argv[3] : '';
        if (StringHelper::hasOptionalParam($argv)) {
            $customer_vat = StringHelper::parseOptionalParam($argv[4]);
        }
    }
    //var_dump($file_data['headers'][0]);
    $calculator = new Calculator($file_data, $currency_array, $output_currency, $customer_vat);
    $invoice = $calculator->calculateTotalInvoice();
    print_r($calculator->displayResult($invoice, $customer_vat));
} catch (Exception $exception) {
    echo "ERROR: " . $exception->getMessage();
}
