<?php
namespace libaocai1990\SourLemonHelper;

/**
 * Format格式化帮助库
 * Class YxFormat
 * @package org
 */
class LmFormat{
    /**
     * 格式化价格为数字
     * @param     $price
     * @param int $number
     * @return string
     */
    public static function format_price($price,$number = 1){
        $price  = 0 + $price;
        return number_format($price, $number);
    }

}