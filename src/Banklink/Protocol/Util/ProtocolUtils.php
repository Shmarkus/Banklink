<?php

namespace Banklink\Protocol\Util;

/**
 * Protocol utilities that are used across all protocols
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  31.10.2012
 */
class ProtocolUtils
{
    /**
     * Generates order reference using 7-3-1 algorithm
     *
     * For more info see http://www.pangaliit.ee/en/settlements-and-standards/reference-number-of-the-invoice
     *
     * @param integer $orderId Order id
     * @param string $prefix
     * @param string $prefixNumberLength Can be max 16
     *
     * @throws \InvalidArgumentException If order id is too long or short
     *
     * @return string
     */
    public static function generateOrderReference($orderId, $prefix = null, $prefixNumberLength = 16)
    {
        $orderId = (string)$orderId;

        /**
         * $prefix is 101
         * We generate reference as let's say if orderId = 5 then 10100000000000000055
         * This prevents conflict between one account with multiple services
         */
        if (!empty($prefix)) {
            $orderId = self::generatePrefixOrderId($prefix, $orderId, $prefixNumberLength);
        }

        $len = strlen($orderId);

        if (1 > $len || 19 < $len) {
            throw new \InvalidArgumentException('OrderId must be between 1 and 19 digits');
        }

        $current = 0;
        $multiplier = array(7, 3, 1);
        $sumProduct = 0;

        for ($i = $len - 1; $i >= 0; $i--) {
            $sumProduct += (int)$orderId[$i] * $multiplier[$current];

            $current = $current < 2 ? ++$current : 0;
        }

        $rounded = ceil($sumProduct/10) * 10;
        $checkSum = $rounded - $sumProduct;

        return $orderId . $checkSum;
    }

    /**
     * Generate full length orderId 
     *
     * @param string $prefix
     * @param integer $orderId
     * @param integer $length
     * @return void
     */
    public static function generatePrefixOrderId($prefix, $orderId, $length = 16)
    {
        return  $prefix . str_pad($orderId, $length, 0, STR_PAD_LEFT);
    }

    /**
     * Convert array values from one encoding to another
     *
     * @param array  $values
     * @param string $inputEncoding
     * @param string $outputEncoding
     *
     * @return array
     */
    public static function convertValues(array $values, $inputEncoding, $outputEncoding)
    {
        return array_map(function($value) use($inputEncoding, $outputEncoding) {
            return mb_convert_encoding($value, $outputEncoding, $inputEncoding);
        }, $values);
    }
}