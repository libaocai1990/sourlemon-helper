<?php
namespace libaocai1990\SourLemonHelper\helper;

class LmRequest
{
    /**
     * 过滤注入
     * @param $request
     */
    public static function filter_injection(&$request)
    {
        $pattern = "/(select[\s])|(insert[\s])|(update[\s])|(delete[\s])|(from[\s])|(where[\s])/i";
        foreach ($request as $k => $v) {
            if (preg_match($pattern, $k, $match)) {
                die("SQL Injection denied!");
            }

            if (is_array($v)) {
                self::filter_injection($v);
            } else {

                if (preg_match($pattern, $v, $match)) {
                    die("SQL Injection denied!");
                }
            }
        }
    }

    /**
     * 过滤请求
     * @param $request
     */
    public static function filter_request(&$request)
    {
        if (MAGIC_QUOTES_GPC) {
            foreach ($request as $k => $v) {
                if (is_array($v)) {
                    self::filter_request($v);
                } else {
                    $request[$k] = stripslashes(trim($v));
                }
            }
        }

    }

    /**
     * adddeepslashes
     * @param $request
     */
    public static function adddeepslashes(&$request)
    {
        foreach ($request as $k => $v) {
            if (is_array($v)) {
                self::adddeepslashes($v);
            } else {
                $request[$k] = addslashes(trim($v));
            }
        }
    }

    /**
     * request转码
     * @param $req
     */
    public static function convert_req(&$req)
    {
        foreach ($req as $k => $v) {
            if (is_array($v)) {
                self::convert_req($req[$k]);
            } else {
                if (!Str::is_u8($v)) {
                    $req[$k] = iconv("gbk", "utf-8", $v);
                }
                $req[$k] = trim($req[$k]); //$s为需要过滤的参数
                $req[$k] = strip_tags($req[$k], ""); //清除HTML如等代码
                $req[$k] = str_replace("\n", "", str_replace(" ", "", $req[$k]));//去掉空格和换行
                $req[$k] = str_replace("\t", "", $req[$k]); //去掉制表符号
                $req[$k] = str_replace("\r\n", "", $req[$k]); //去掉回车换行符号
                $req[$k] = str_replace("\r", "", $req[$k]); //去掉回车
                $req[$k] = str_replace("'", "", $req[$k]); //去掉单引号
                $req[$k] = str_replace("../", "", $req[$k]); //去掉"../"
                $req[$k] = trim($req[$k]);
            }
        }
    }

}