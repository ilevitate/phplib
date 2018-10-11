<?php
/**
 * Created by PhpStorm.
 * User: iLevitate
 * Date: 2018/10/11
 * Time: 11:17
 */

namespace Utils;


class PriceUtil
{
    /**
     * 格式化金钱
     * @param $price
     * @param int $unit
     * @param int $decimal
     * @return float
     */
    public static function formatPrice($price, $unit = 100, $decimal = 2)
    {
        return round($price / $unit, $decimal);
    }


}