<?php

namespace Helpers;

use Config\Constants;
use Exception;

/**
 * Class CurrencyHelper
 * @package InvoiceApp\app\CurrencyHelper
 */
class CurrencyHelper
{
    /**
     * Checks if provided currencies match allowed ones
     * @param $string
     * @return bool
     * @throws Exception
     */
    public static function isAllowedCurrency($string)
    {
        if (strpos(Constants::ACCEPTED_CURRENCIES, strtoupper($string)) === false) {
            throw new Exception("Unsupported currency listed in currency exchange rate or output currency parameter! List of supported currencies: " .
                Constants::ACCEPTED_CURRENCIES);
        }
        return true;
    }

    /**
     * Exchange to output currency
     * @param $total
     * @param $current_currency
     * @param $curr_arr
     * @param $output_currency
     * @return mixed
     * @throws Exception
     */
    public static function exchangeToOutputCurr($vals, $curr_arr, $output_currency)
    {
        // We first convert to default currency
        $default_currency = StringHelper::extractDefaultCurrency($curr_arr);
        if ($vals['Currency'] != $default_currency) {
            $vals['Total'] /= $curr_arr[$vals['Currency']];
        }
        // Now to output
        $vals['Total'] *= $curr_arr[$output_currency];
        return $vals['Total'];
    }
}