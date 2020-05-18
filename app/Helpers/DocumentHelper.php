<?php

namespace Helpers;

use Exception;

/**
 * Class Document
 * @package InvoiceApp\app\Document
 */
class DocumentHelper
{
    /**
     * Check if file is in correct CSV format
     * @param $file
     * @return bool
     */
    public static function isCSV($file)
    {
        $csv_mime_types = [
            'text/csv',
            'text/plain',
            'application/csv',
            'text/comma-separated-values',
            'application/excel',
            'application/vnd.ms-excel',
            'application/vnd.msexcel',
            'text/anytext',
            'application/octet-stream',
            'application/txt',
        ];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file);

        return in_array($mime_type, $csv_mime_types);
    }

    /**
     * Parses the input stream file to an array
     * @param $filename
     * @param string $delimiter
     * @return array
     * @throws Exception
     */
    public static function parseCSV($filename, $delimiter = ',')
    {
        if (!is_readable($filename) || !DocumentHelper::isCSV($filename)) {
            throw new Exception("Invalid file path or type!");
        }
        $header = null;
        $data = array();
        // Read and print the entire contents of the CSV file
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                    //$data['headers'] = [$header];
                }
            }
            fclose($handle);
        }
        return $data;
    }

    /**
     * Check to see if CSV contains given ID
     * @param $optional_param
     * @param $all_vats
     * @return bool
     */
    public static function VatExists($optional_param, $all_vats)
    {
        if (array_key_exists($optional_param, $all_vats)) {
            return true;
        }
        return false;
    }

    public static function checkParent($vals, $res)
    {
        $child = $vals['Parent document'];
        $parent = isset($res[$vals['Vat number']]['Document number']) ? $res[$vals['Vat number']]['Document number'] : '';
        if ($parent != '' && $child == $parent) {
            return true;
        }
        throw new Exception('Missing parent document: ' . $child);
    }
}