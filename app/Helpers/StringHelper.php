<?php

namespace Helpers;

use Exception;

/**
 * Class StringHelper
 * @package Helpers
 */
class StringHelper
{
    /**
     * Checks if all the required input parameters are present
     * @param $input_stream
     * @return bool
     * @throws Exception
     */
    public static function isFullInput($input_stream)
    {
        if (count($input_stream) < 4) {
            throw new Exception("Missing required input parameters'! File, exchange rates and output currency are required!");
        } else {
            if (count($input_stream) > 5) {
                throw new Exception("Too many input params! Max allowed are 5.");
            }
        }
        return true;
    }

    /**
     * Indicates whether we have an optional param specified
     * @param $input_stream
     * @return bool
     */
    public static function hasOptionalParam($input_stream)
    {
        return count($input_stream) == 5;
    }

    /**
     * Parses the second required parameter to an array
     * @param $currency_exchange
     * @return array
     * @throws Exception
     */
    public static function parseExchangeString($currency_exchange)
    {
        $parsed_arr = [];
        $curr_items = explode(",", $currency_exchange);
        if (count($curr_items) < 3) {
            throw new Exception("At least 2 currencies are needed, in addition to the default one!");
        }
        foreach ($curr_items as $currency_rate) {
            $currency_rate_items = explode(":", $currency_rate);
            if (CurrencyHelper::isAllowedCurrency(strtoupper($currency_rate_items[0]))) {
                $parsed_arr[$currency_rate_items[0]] = $currency_rate_items[1];
            }
        }
        return $parsed_arr;
    }

    /**
     * Extracts the VAT number from the optional parameter
     * @param $string
     * @return false|string
     * @throws Exception
     */
    public static function parseOptionalParam($string)
    {
        if (substr($string, 0, 5) == '--vat') {
            return substr($string, 6);
        } else {
            throw new Exception("Optional parameter should be in the form --vat=VAT_NUMBER");
        }
    }

    /**
     * Gets the default currency from the input string
     * @param $arr
     * @return string
     * @throws Exception
     */
    public static function extractDefaultCurrency($arr)
    {
        $arr_check = [];
        foreach ($arr as $key => $value) {
            if ($value == 1) {
                array_push($arr_check, $key);
            }
        }
        if (count($arr_check) > 1){
            throw new Exception('There can only be one default currency!');
        }
        return $arr_check[0];
    }
}
