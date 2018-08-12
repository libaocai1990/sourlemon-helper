<?php
namespace libaocai1990\SourLemonHelper\helper;

/**
 * 位置距离换算
 * Class LmDistance
 * @package Api\Common\Core
 */
class LmDistance
{
    /**
     * 获取周围坐标
     * @param       $lng
     * @param       $lat
     * @param float $distance
     * @return array
     */
    public static function returnSquarePoint($lng, $lat,$distance = 0.5){
        $dlng = 2 * asin(sin($distance / (2 * 6371)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);

        $dlat = $distance / 6371;
        $dlat = rad2deg($dlat);

        return array(
            'left_top'     => array('lat' => $lat + $dlat, 'lng' => $lng - $dlng),
            'right_top'    => array('lat' => $lat + $dlat, 'lng' => $lng + $dlng),
            'left_bottom'  => array('lat' => $lat - $dlat, 'lng' => $lng - $dlng),
            'right_bottom' => array('lat' => $lat - $dlat, 'lng' => $lng + $dlng)
        );
    }

    /**
     * 计算两个坐标的直线距离
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     * @return float
     */
    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6378138; //近似地球半径米
        // 转换为弧度
        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;
        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;
        // 使用半正矢公式  用尺规来计算
        $calcLongitude      = $lng2 - $lng1;
        $calcLatitude       = $lat2 - $lat1;
        $stepOne            = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo            = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance/1000,2);
    }
}

