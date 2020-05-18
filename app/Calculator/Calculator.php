<?php

namespace Calculator;

use Config\Constants;
use Exception;
use Helpers\CurrencyHelper;
use Helpers\DocumentHelper;

/**
 * Class Calculator
 * @package InvoiceApp\app\Calculator
 */
class Calculator
{
    /**
     * @var
     */
    private $data;
    /**
     * @var
     */
    private $exchange_array;
    /**
     * @var
     */
    private $optional_param;
    /**
     * @var
     */
    private $output_currency;

    /**
     * Calculator constructor.
     * @param null $file_data
     * @param null $exch_array
     * @param null $opt_param
     * @param null $output_currency
     */
    public function __construct(
        $file_data,
        $exch_array,
        $output_currency,
        $opt_param
    ) {
        $this->data = $file_data;
        $this->exchange_array = $exch_array;
        $this->optional_param = $opt_param;
        $this->output_currency = $output_currency;
    }

    /**
     * Calculate the total invoice amount of all the sum of the invoice documents
     * @return array|mixed
     * @throws Exception
     */
    public function calculateTotalInvoice()
    {
        $res = [];
        foreach ($this->data as $vals) {
            // If invoice currency is different from output exchange
            if ($vals['Currency'] != $this->output_currency) {
                $vals['Total'] = CurrencyHelper::exchangeToOutputCurr($vals,
                    $this->exchange_array, $this->output_currency);
                $vals['Currency'] = $this->output_currency;
            }
            // Check for missing parent docs
            if ($vals['Parent document'] != '') {
                DocumentHelper::checkParent($vals, $res);
            }
            // Get same customer data and sum it
            if (array_key_exists($vals['Vat number'], $res)) {
                if ($vals['Type'] == Constants::CREDIT_NOTE) {
                    if ($vals['Total'] > $res[$vals['Vat number']]['Total']) {
                        throw new Exception('Credit note is higher than invoice amount for document: ' . $vals['Parent document']);
                    }
                    //DocumentHelper::checkParent($vals['Parent document']);
                    $res[$vals['Vat number']]['Total'] -= $vals['Total'];
                } else {
                    $res[$vals['Vat number']]['Total'] += $vals['Total'];
                }
            } else {
                $res[$vals['Vat number']] = $vals;
            }

        }
        // If we have an optional param take only specific VAT
        if ($this->optional_param != null && DocumentHelper::VatExists($this->optional_param,
                $res)) {
            if (!DocumentHelper::VatExists($this->optional_param,
                $res)) {
                throw new Exception('Given CSV does not match any record.');
            }
            $res = $res[$this->optional_param];
        }
        return $res;
    }

    /**
     * Output styled results
     * @param $array
     * @param $opt_param
     */
    public function displayResult($array, $opt_param)
    {
        if ($opt_param != null) {
            return "Customer " . $array['Customer'] . " Total: " . round($array['Total'],
                    2) . " " . $array['Currency'] . ".";
        } else {
            $tmp_string = '';
            foreach ($array as $array_item) {
                $tmp_string .= "Customer " . $array_item['Customer'] . " Total: " . round($array_item['Total'],
                        2) . " " . $array_item['Currency'] . ".\n";
            }
            return $tmp_string;
        }
    }
}



