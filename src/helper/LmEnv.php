<?php
namespace libaocai1990\SourLemonHelper\helper;

class LmEnv
{

    /**
     * 获取客户端IP
     * @return string
     */
    public static function get_client_ip()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset ($_SERVER ['REMOTE_ADDR']) && $_SERVER ['REMOTE_ADDR'] && strcasecmp($_SERVER ['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER ['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return ($ip);
    }


    /**
     * gzip_out gzip输出
     * @param $content
     */
    public static function gzip_out($content)
    {
        header("Content-type: text/html; charset=utf-8");
        header("Cache-control: private");  //支持页面回跳
        $gzip = env('GZIP_ON');
        if ($gzip == 1) {
            if (!headers_sent() && extension_loaded("zlib") && preg_match("/gzip/i", $_SERVER["HTTP_ACCEPT_ENCODING"])) {
                $content = gzencode($content, 9);
                header("Content-Encoding: gzip");
                header("Content-Length: " . strlen($content));
                echo $content;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }

    /**
     * 获取当前访问的设备类型
     * @return integer 1：其他  2：iOS  3：Android
     */
    public static function get_device_type()
    {
        //全部变成小写字母
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $type  = 1;
        //分别进行判断
        if (strpos($agent, 'iphone') !== false || strpos($agent, 'ipad') !== false) {
            $type = 2;
        }
        if (strpos($agent, 'android') !== false) {
            $type = 3;
        }
        return $type;
    }

    /**
     * 检测是否是手机访问
     * @return bool
     */
    public static function is_mobile()
    {
        $useragent               = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $useragent_commentsblock = preg_match('|\(.*?\)|', $useragent, $matches) > 0 ? $matches[0] : '';
        function _is_mobile($substrs, $text)
        {
            foreach ($substrs as $substr)
                if (false !== strpos($text, $substr)) {
                    return true;
                }
            return false;
        }

        $mobile_os_list    = array('Google Wireless Transcoder', 'Windows CE', 'WindowsCE', 'Symbian', 'Android', 'armv6l', 'armv5', 'Mobile', 'CentOS', 'mowser', 'AvantGo', 'Opera Mobi', 'J2ME/MIDP', 'Smartphone', 'Go.Web', 'Palm', 'iPAQ');
        $mobile_token_list = array('Profile/MIDP', 'Configuration/CLDC-', '160×160', '176×220', '240×240', '240×320', '320×240', 'UP.Browser', 'UP.Link', 'SymbianOS', 'PalmOS', 'PocketPC', 'SonyEricsson', 'Nokia', 'BlackBerry', 'Vodafone', 'BenQ', 'Novarra-Vision', 'Iris', 'NetFront', 'HTC_', 'Xda_', 'SAMSUNG-SGH', 'Wapaka', 'DoCoMo', 'iPhone', 'iPod');

        $found_mobile = _is_mobile($mobile_os_list, $useragent_commentsblock) ||
            _is_mobile($mobile_token_list, $useragent);
        if ($found_mobile) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string 当前文件的名称
     */
    public static function self()
    {
        return isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : (isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['ORIG_PATH_INFO']);
    }

    /**
     * @return string 来源地址
     */
    public static function referer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }

    /**
     * @return string 返回服务器名称
     */
    public static function domain()
    {
        return $_SERVER['SERVER_NAME'];
    }


    /**
     * @return mixed
     */
    public static function get_host()
    {
        /* 域名或IP地址 */
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        } elseif (isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } else {
            if (isset($_SERVER['SERVER_NAME'])) {
                $host = $_SERVER['SERVER_NAME'];
            } elseif (isset($_SERVER['SERVER_ADDR'])) {
                $host = $_SERVER['SERVER_ADDR'];
            }
        }
        return $host;
    }

    /**
     * @return string
     */
    public static function get_http()
    {
        return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
    }

    /**
     * @return string
     */
    public static function get_domain()
    {
        /* 协议 */
        $protocol = self::get_http();
        /* 域名或IP地址 */
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        } elseif (isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } else {
            /* 端口 */
            if (isset($_SERVER['SERVER_PORT'])) {
                $port = ':' . $_SERVER['SERVER_PORT'];
                if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol)) {
                    $port = '';
                }
            } else {
                $port = '';
            }

            if (isset($_SERVER['SERVER_NAME'])) {
                $host = $_SERVER['SERVER_NAME'] . $port;
            } elseif (isset($_SERVER['SERVER_ADDR'])) {
                $host = $_SERVER['SERVER_ADDR'] . $port;
            }
        }
        return $protocol . $host;
    }


    /**
     * @return string 协议名称
     */
    public static function scheme()
    {
        if (!isset($_SERVER['SERVER_PORT'])) {
            return 'http://';
        }
        return $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    }

    /**
     * @return string 返回端口号
     */
    public static function port()
    {
        if (!isset($_SERVER['SERVER_PORT'])) {
            return '';
        }
        return $_SERVER['SERVER_PORT'] == '80' ? '' : ':' . $_SERVER['SERVER_PORT'];
    }

    /**
     * @return string 完整的地址
     */
    public static function uri()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $uri = $_SERVER['REQUEST_URI'];
        } else {
            $uri = $_SERVER['PHP_SELF'];
            if (isset($_SERVER['argv'])) {
                if (isset($_SERVER['argv'][0])) $uri .= '?' . $_SERVER['argv'][0];
            } else {
                $uri .= '?' . $_SERVER['QUERY_STRING'];
            }
        }
        $uri = LmStr::htmlSpecialChars($uri);
        return self::scheme() . self::host() . (strpos(self::host(), ':') === false ? self::port() : '') . $uri;
    }

    /**
     * 获取主机
     * @return string
     */
    public static function host()
    {
        return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    }

    /**
     * @return string   没有查询的完整的URL地址, 基于当前页面
     */
    public static function nquri()
    {
        return self::scheme() . self::host() . (strpos(self::host(), ':') === false ? self::port() : '') . self::self();
    }

    /**
     * 请求的unix 时间戳
     * @return int
     */
    public static function time()
    {
        return $_SERVER['REQUEST_TIME'];
    }

}