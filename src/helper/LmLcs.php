<?php
namespace libaocai1990\SourLemonHelper\helper;

/**
 * 计算文章或字符串相似度
 * Class LmLcs
 * 调用示例：
 * $lmc = new LmLcs();
 * $lmc->getSimilar($str1,$str2);
 * @package App\Core\Helper
 */
class LmLcs
{
    protected static $str1;
    protected static $str2;
    protected static $c = array();

    /**
     * 返回串一和串二的最长公共子序列
     * @param     $str1
     * @param     $str2
     * @param int $len1
     * @param int $len2
     * @return string
     */
    public static function getLCS($str1, $str2, $len1 = 0, $len2 = 0)
    {
        self::$str1 = $str1;
        self::$str2 = $str2;
        if ($len1 == 0) $len1 = strlen($str1);
        if ($len2 == 0) $len2 = strlen($str2);
        self::initC($len1, $len2);
        return self::printLCS(self::$c, $len1 - 1, $len2 - 1);
    }

    /**
     * 返回两个串的相似度
     * @param $str1
     * @param $str2
     * @return float|int
     */
    public static function getSimilar($str1, $str2)
    {
        $len1 = strlen($str1);
        $len2 = strlen($str2);
        $len  = strlen(self::getLCS($str1, $str2, $len1, $len2));
        return $len * 2 / ($len1 + $len2);
    }

    public static function initC($len1, $len2)
    {
        for ($i = 0; $i < $len1; $i++) self::$c[$i][0] = 0;
        for ($j = 0; $j < $len2; $j++) self::$c[0][$j] = 0;
        for ($i = 1; $i < $len1; $i++) {
            for ($j = 1; $j < $len2; $j++) {
                if (self::$str1[$i] == self::$str2[$j]) {
                    self::$c[$i][$j] = self::$c[$i - 1][$j - 1] + 1;
                } else if (self::$c[$i - 1][$j] >= self::$c[$i][$j - 1]) {
                    self::$c[$i][$j] = self::$c[$i - 1][$j];
                } else {
                    self::$c[$i][$j] = self::$c[$i][$j - 1];
                }
            }
        }
    }

    public static function printLCS($c, $i, $j)
    {
        if ($i == 0 || $j == 0) {
            if (self::$str1[$i] == self::$str2[$j]) return self::$str2[$j];
            else return "";
        }
        if (self::$str1[$i] == self::$str2[$j]) {
            return self::printLCS(self::$c, $i - 1, $j - 1) . self::$str2[$j];
        } else if (self::$c[$i - 1][$j] >= self::$c[$i][$j - 1]) {
            return self::printLCS(self::$c, $i - 1, $j);
        } else {
            return self::printLCS(self::$c, $i, $j - 1);
        }
    }
}