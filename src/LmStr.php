<?php
namespace libaocai1990\SourLemonHelper;

/**
 * Class LmStr
 *
 * @package app\Core\Helper
 */
class LmStr
{

    /** 检测是否是json字符串 */
    public static function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /** json格式化函数 */
    public static function formatJson($json)
    {
        $result          = '';
        $level           = 0;
        $prev_char       = '';
        $in_quotes       = false;
        $ends_line_level = NULL;
        $json_length     = strlen($json);

        for ($i = 0; $i < $json_length; $i++) {
            $char           = $json[$i];
            $new_line_level = NULL;
            $post           = "";
            if ($ends_line_level !== NULL) {
                $new_line_level  = $ends_line_level;
                $ends_line_level = NULL;
            }
            if ($char === '"' && $prev_char != '\\') {
                $in_quotes = !$in_quotes;
            } else if (!$in_quotes) {
                switch ($char) {
                    case '}':
                    case ']':
                        $level--;
                        $ends_line_level = NULL;
                        $new_line_level  = $level;
                        break;

                    case '{':
                    case '[':
                        $level++;
                    case ',':
                        $ends_line_level = $level;
                        break;

                    case ':':
                        $post = " ";
                        break;

                    case " ":
                    case "\t":
                    case "\n":
                    case "\r":
                        $char            = "";
                        $ends_line_level = $new_line_level;
                        $new_line_level  = NULL;
                        break;
                }
            }
            if ($new_line_level !== NULL) {
                $result .= "\n" . str_repeat("    ", $new_line_level);
            }
            $result    .= $char . $post;
            $prev_char = $char;
        }

        return $result;
    }


    /**
     * 隐藏联系方式
     * @param $input
     * @return mixed|string
     */
    public static function hideMobile($input)
    {
        if ($input) {
            return substr_replace($input, '****', 3, -4);
        } else {
            return '';
        }
    }

    /**
     * 隐藏邮箱
     * @param $input
     * @return mixed|string
     */
    public static function hideEmail($input)
    {
        if ($input) {
            return substr_replace($input, '****', 3, strpos($input, '@') - 3);
        } else {
            return '';
        }
    }


    /**
     * Trim Slashes
     * @param $str
     * @return string
     */
    public static function trim_slashes($str)
    {
        return trim($str, '/');
    }

    /**
     * Strip Slashes
     * @param $str
     * @return array|string
     */
    public static function strip_slashes($str)
    {
        if (!is_array($str)) {
            return stripslashes($str);
        }

        foreach ($str as $key => $val) {
            $str[$key] = strip_slashes($val);
        }

        return $str;
    }

    /**
     * Strip Quotes
     * @param $str
     * @return mixed
     */
    public static function strip_quotes($str)
    {
        return str_replace(array('"', "'"), '', $str);
    }

    /**
     * Quotes to Entities
     * @param $str
     * @return mixed
     */
    public static function quotes_to_entities($str)
    {
        return str_replace(array("\'", "\"", "'", '"'), array("&#39;", "&quot;", "&#39;", "&quot;"), $str);
    }

    /**
     * Reduce Double Slashes
     * @param $str
     * @return mixed
     */
    public static function reduce_double_slashes($str)
    {
        return preg_replace('#(^|[^:])//+#', '\\1/', $str);
    }

    /**
     * Reduce Multiples
     * @param        $str
     * @param string $character
     * @param bool   $trim
     * @return mixed|string
     */
    public static function reduce_multiples($str, $character = ',', $trim = FALSE)
    {
        $str = preg_replace('#' . preg_quote($character, '#') . '{2,}#', $character, $str);
        return ($trim === TRUE) ? trim($str, $character) : $str;
    }

    /**
     * 检测是否含有空格符
     * @param $value
     * @return int
     */
    public static function hasSpace($value)
    {
        return preg_match('/\s+/', $value);
    }

    /**
     * 取消转义
     * @param $input
     * @return array|string
     */
    public static function stripSlashes($input)
    {
        return is_array($input) ? array_map([__CLASS__, __FUNCTION__], $input) : stripslashes($input);
    }

    /**
     * 转义操作
     * @param $input
     * @return array|string
     */
    public static function addSlashes($input)
    {
        return is_array($input) ? array_map([__CLASS__, __FUNCTION__], $input) : addslashes($input);
    }

    /**
     * Create a Random String
     * @param string $type
     * @param int    $len
     * @return int|string
     */
    public static function random_string($type = 'alnum', $len = 8)
    {
        switch ($type) {
            case 'basic':
                return mt_rand();
            case 'alnum':
            case 'numeric':
            case 'nozero':
            case 'alpha':
                switch ($type) {
                    case 'alpha':
                        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum':
                        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric':
                        $pool = '0123456789';
                        break;
                    case 'nozero':
                        $pool = '123456789';
                        break;
                }
                return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
            case 'unique': // todo: remove in 3.1+
            case 'md5':
                return md5(uniqid(mt_rand()));
            case 'encrypt': // todo: remove in 3.1+
            case 'sha1':
                return sha1(uniqid(mt_rand(), TRUE));
        }
    }

    /**
     * Add's _1 to a string or increment the ending number to allow _2, _3, etc
     * @param        $str
     * @param string $separator
     * @param int    $first
     * @return string
     */
    public static function increment_string($str, $separator = '_', $first = 1)
    {
        preg_match('/(.+)' . $separator . '([0-9]+)$/', $str, $match);
        return isset($match[2]) ? $match[1] . $separator . ($match[2] + 1) : $str . $separator . $first;
    }

    /**
     * Alternator
     * @param $args
     * @return string
     */
    public static function alternator($args)
    {
        static $i;

        if (func_num_args() === 0) {
            $i = 0;
            return '';
        }
        $args = func_get_args();
        return $args[($i++ % count($args))];
    }

    /**
     * Repeater function
     * @param     $data
     * @param int $num
     * @return string
     */
    public static function repeater($data, $num = 1)
    {
        return ($num > 0) ? str_repeat($data, $num) : '';
    }

    /**
     * is_u8
     * @param $string
     * @return int
     */
    public static function is_u8($string)
    {
        return preg_match('%^(?:
			 [\x09\x0A\x0D\x20-\x7E]            # ASCII
		   | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
		   |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
		   | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
		   |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
		   |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
		   | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
		   |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
	   )*$%xs', $string);
    }

    /**
     * utf8 字符串截取
     * @param        $str
     * @param int    $start
     * @param int    $length
     * @param string $charset
     * @param bool   $suffix
     * @return string
     */
    public static function unicode_substr($str, $start = 0, $length = 15, $charset = "utf-8", $suffix = true)
    {
        if (function_exists("mb_substr")) {
            $slice = mb_substr($str, $start, $length, $charset);
            if ($suffix & $slice != $str) return $slice . "…";
            return $slice;
        } elseif (function_exists('iconv_substr')) {
            return iconv_substr($str, $start, $length, $charset);
        }
        $re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
        if ($suffix && $slice != $str) return $slice . "…";
        return $slice;
    }

    /**
     * 随机产生六位数密码Begin
     * @param int    $len
     * @param string $format
     *
     * @return string
     */
    public static function randStr($len = 6, $format = "ALL")
    {
        switch ($format) {
            case "ALL":
                $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~";
                break;
            case "CHAR":
                $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-@#~";
                break;
            case "NUMBER":
                $chars = "0123456789";
                break;
            default:
                $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~";
                break;
        }

        mt_srand((double)microtime() * 1000000 * getmypid());
        $password = "";
        while (strlen($password) < $len) {
            $password .= substr($chars, (mt_rand() % strlen($chars)), 1);
        }
        return $password;
    }

    /**
     * 生成一定数量的随机数，并且不重复
     * @param integer $number 数量
     * @param string  $len    长度
     * @param string  $type   字串类型
     *                        0 字母 1 数字 其它 混合
     * @return string
     */
    public static function build_count_rand($number, $length = 4, $mode = 1)
    {
        if ($mode == 1 && $length < strlen($number)) {
            //不足以生成一定数量的不重复数字
            return false;
        }
        $rand = array();
        for ($i = 0; $i < $number; $i++) {
            $rand[] = rand_string($length, $mode);
        }
        $unqiue = array_unique($rand);
        if (count($unqiue) == count($rand)) {
            return $rand;
        }
        $count = count($rand) - count($unqiue);
        for ($i = 0; $i < $count * 3; $i++) {
            $rand[] = rand_string($length, $mode);
        }
        $rand = array_slice(array_unique($rand), 0, $number);
        return $rand;
    }


    /**
     * 转义特殊字符
     * @param      $input
     * @param bool $preserveAmpersand
     * @return array|mixed|string
     */
    public static function htmlSpecialChars($input, $preserveAmpersand = true)
    {
        if (is_string($input)) {
            if ($preserveAmpersand) {
                return str_replace('&amp;', '&', htmlspecialchars($input, ENT_QUOTES));
            } else {
                return htmlspecialchars($input, ENT_QUOTES);
            }
        }
        if (is_array($input)) {
            foreach ($input as $key => $val) {
                $input[$key] = self::htmlSpecialChars($val, $preserveAmpersand);
            }
            return $input;
        }
        return $input;
    }

    /**
     * 格式处理字符串实体
     * @param $string
     * @return string
     */
    public static function name_format($string)
    {
        return html_entity_decode($string, ENT_COMPAT, 'UTF-8');//转换html实体
    }


    /**
     * 生成不重复的随机数
     * @param  int $start  需要生成的数字开始范围
     * @param  int $end    结束范围
     * @param  int $length 需要生成的随机数个数
     * @return array       生成的随机数
     */
    public static function get_rand_number($start = 1, $end = 10, $length = 4)
    {
        $connt = 0;
        $temp  = array();
        while ($connt < $length) {
            $temp[] = rand($start, $end);
            $data   = array_unique($temp);
            $connt  = count($data);
        }
        sort($data);
        return $data;
    }


    /**
     * 获取一定范围内的随机数字
     * 跟rand()函数的区别是 位数不足补零 例如
     * rand(1,9999)可能会得到 465
     * rand_number(1,9999)可能会得到 0465  保证是4位的
     * @param integer $min 最小值
     * @param integer $max 最大值
     * @return string
     */
    public static function rand_number($min = 1, $max = 9999)
    {
        return sprintf("%0" . strlen($max) . "d", mt_rand($min, $max));
    }

    /**
     * 字符串截取，支持中文和其他编码
     * @param string $str     需要转换的字符串
     * @param string $start   开始位置
     * @param string $length  截取长度
     * @param string $suffix  截断显示字符
     * @param string $charset 编码格式
     * @return string
     */
    public static function re_substr($str, $start = 0, $length, $suffix = true, $charset = "utf-8")
    {
        if (function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
        } else {
            $re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("", array_slice($match[0], $start, $length));
        }
        $omit = mb_strlen($str) >= $length ? '...' : '';
        return $suffix ? $slice . $omit : $slice;
    }

    /**
     * 删除指定的标签和内容
     * @param     $tags    需要删除的标签数组
     * @param     $str     数据源
     * @param int $content 是否删除标签内的内容 0保留内容 1不保留内容
     * @return mixed
     */
    public static function strip_html_tags($tags, $str, $content = 0)
    {
        if ($content) {
            $html = array();
            foreach ($tags as $tag) {
                $html[] = '/(<' . $tag . '.*?>[\s|\S]*?<\/' . $tag . '>)/';
            }
            $data = preg_replace($html, '', $str);
        } else {
            $html = array();
            foreach ($tags as $tag) {
                $html[] = "/(<(?:\/" . $tag . "|" . $tag . ")[^>]*>)/i";
            }
            $data = preg_replace($html, '', $str);
        }
        return $data;
    }


    /**
     * 添加商品名样式
     * @param   string $goods_name 商品名称
     * @param   string $style      样式参数
     * @return  string
     */
    public static function add_style($goods_name, $style)
    {
        $goods_style_name = $goods_name;
        $arr              = explode('+', $style);
        $font_color       = !empty($arr[0]) ? $arr[0] : '';
        $font_style       = !empty($arr[1]) ? $arr[1] : '';

        if ($font_color != '') {
            $goods_style_name = '<font color=' . $font_color . '>' . $goods_style_name . '</font>';
        }
        if ($font_style != '') {
            $goods_style_name = '<' . $font_style . '>' . $goods_style_name . '</' . $font_style . '>';
        }
        return $goods_style_name;
    }

    /**
     * 格式化商品价格
     * @param        $price 商品价格
     * @param bool   $change_price
     * @param int    $price_format
     * @param string $currency_format
     * @return string
     */
    public static function price_format($price, $change_price = true, $price_format = 0, $currency_format = '￥%s')
    {
        if ($price === '') {
            $price = 0;
        }
        if ($change_price) {
            switch ($price_format) {
                case 0:
                    $price = number_format($price, 2, '.', '');
                    break;
                case 1: // 保留不为 0 的尾数
                    $price = preg_replace('/(.*)(\\.)([0-9]*?)0+$/', '\1\2\3', number_format($price, 2, '.', ''));

                    if (substr($price, -1) == '.') {
                        $price = substr($price, 0, -1);
                    }
                    break;
                case 2: // 不四舍五入，保留1位
                    $price = substr(number_format($price, 2, '.', ''), 0, -1);
                    break;
                case 3: // 直接取整
                    $price = intval($price);
                    break;
                case 4: // 四舍五入，保留 1 位
                    $price = number_format($price, 1, '.', '');
                    break;
                case 5: // 先四舍五入，不保留小数
                    $price = round($price);
                    break;
            }
        } else {
            $price = number_format($price, 2, '.', '');
        }

        return sprintf($currency_format, $price);
    }

    /**
     * 字符串转换为数组，主要用于把分隔符调整到第二个参数
     * @param  string $str  要分割的字符串
     * @param  string $glue 分割符
     * @return array
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public static function str2arr($str, $glue = '-')
    {
        return explode($glue, $str);
    }

    /**
     * 数组转换为字符串，主要用于把分隔符调整到第二个参数
     * @param  array  $arr  要连接的数组
     * @param  string $glue 分割符
     * @return string
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public static function arr2str($arr, $glue = ',')
    {
        return implode($glue, $arr);
    }

    /**
     * 汉子转拼音
     * @param        $_String
     * @param string $_Code
     * @return mixed
     */
    public static function Pinyin($_String, $_Code = 'gb2312')
    {
        $_DataKey    = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha" .
            "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|" .
            "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er" .
            "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui" .
            "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang" .
            "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang" .
            "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue" .
            "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne" .
            "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen" .
            "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang" .
            "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|" .
            "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|" .
            "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu" .
            "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you" .
            "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|" .
            "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
        $_DataValue  = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990" .
            "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725" .
            "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263" .
            "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003" .
            "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697" .
            "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211" .
            "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922" .
            "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468" .
            "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664" .
            "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407" .
            "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959" .
            "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652" .
            "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369" .
            "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128" .
            "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914" .
            "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645" .
            "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149" .
            "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087" .
            "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658" .
            "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340" .
            "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888" .
            "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585" .
            "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847" .
            "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055" .
            "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780" .
            "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274" .
            "|-10270|-10262|-10260|-10256|-10254";
        $_TDataKey   = explode('|', $_DataKey);
        $_TDataValue = explode('|', $_DataValue);
        $_Data       = (PHP_VERSION >= '5.0') ? array_combine($_TDataKey, $_TDataValue) : Arr::_Array_Combine($_TDataKey, $_TDataValue);
        arsort($_Data);
        reset($_Data);
        if ($_Code != 'gb2312') $_String = self::_U2_Utf8_Gb($_String);
        $_Res = '';
        for ($i = 0; $i < strlen($_String); $i++) {
            $_P = ord(substr($_String, $i, 1));
            if ($_P > 160) {
                $_Q = ord(substr($_String, ++$i, 1));
                $_P = $_P * 256 + $_Q - 65536;
            }
            $_Res .= self::_Pinyin($_P, $_Data);
        }
        return preg_replace("/[^a-z0-9]*/", '', $_Res);
    }

    /**
     * 字符编码转换
     * @param $_C
     * @return string
     */
    public static function _U2_Utf8_Gb($_C)
    {
        $_String = '';
        if ($_C < 0x80) {
            $_String .= $_C;
        } elseif ($_C < 0x800) {
            $_String .= chr(0xC0 | $_C >> 6);
            $_String .= chr(0x80 | $_C & 0x3F);
        } elseif ($_C < 0x10000) {
            $_String .= chr(0xE0 | $_C >> 12);
            $_String .= chr(0x80 | $_C >> 6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        } elseif ($_C < 0x200000) {
            $_String .= chr(0xF0 | $_C >> 18);
            $_String .= chr(0x80 | $_C >> 12 & 0x3F);
            $_String .= chr(0x80 | $_C >> 6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        }
        return iconv('UTF-8', 'GB2312', $_String);
    }

    /**
     * unicode_decode
     * @param $name
     * @return string
     */
    public static function unicode_decode($name)
    {//Unicode to
        $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
        preg_match_all($pattern, $name, $matches);
        if (!empty($matches)) {
            $name = '';
            for ($j = 0; $j < count($matches[0]); $j++) {
                $str = $matches[0][$j];
                if (strpos($str, '\\u') === 0) {
                    $code  = base_convert(substr($str, 2, 2), 16, 10);
                    $code2 = base_convert(substr($str, 4), 16, 10);
                    $c     = chr($code) . chr($code2);
                    $c     = iconv('UCS-2', 'UTF-8', $c);
                    $name  .= $c;
                } else {
                    $name .= $str;
                }
            }
        }
        return $name;
    }

    /**
     * unicode_encode
     * @param $name
     * @return string
     */
    public static function unicode_encode($name)
    {//to Unicode
        $name = iconv('UTF-8', 'UCS-2', $name);
        $len  = strlen($name);
        $str  = '';
        for ($i = 0; $i < $len - 1; $i = $i + 2) {
            $c  = $name[$i];
            $c2 = $name[$i + 1];
            if (ord($c) > 0) {// 两个字节的字
                $cn_word = '\\' . base_convert(ord($c), 10, 16) . base_convert(ord($c2), 10, 16);
                $str     .= strtoupper($cn_word);
            } else {
                $str .= $c2;
            }
        }
        return $str;
    }

    /**
     * str_to_unicode_word utf8字符串分隔为unicode字符串
     * @param        $str
     * @param string $depart
     * @return string
     */
    public static function str_to_unicode_word($str, $depart = ' ')
    {
        $arr     = array();
        $str_len = mb_strlen($str, 'utf-8');
        for ($i = 0; $i < $str_len; $i++) {
            $s = mb_substr($str, $i, 1, 'utf-8');
            if ($s != ' ' && $s != '　') {
                $arr[] = 'ux' . self::utf8_to_unicode($s);
            }
        }
        return implode($depart, $arr);
    }

    /**
     * utf8字符转Unicode字符
     * @param $char
     * @return int
     */
    public static function utf8_to_unicode($char)
    {
        switch (strlen($char)) {
            case 1:
                return ord($char);
            case 2:
                $n = (ord($char[0]) & 0x3f) << 6;
                $n += ord($char[1]) & 0x3f;
                return $n;
            case 3:
                $n = (ord($char[0]) & 0x1f) << 12;
                $n += (ord($char[1]) & 0x3f) << 6;
                $n += ord($char[2]) & 0x3f;
                return $n;
            case 4:
                $n = (ord($char[0]) & 0x0f) << 18;
                $n += (ord($char[1]) & 0x3f) << 12;
                $n += (ord($char[2]) & 0x3f) << 6;
                $n += ord($char[3]) & 0x3f;
                return $n;
        }
    }

    /**
     * utf8字符串分隔为unicode字符串
     * @param $str
     * @return string
     */
    public static function str_to_unicode_string($str)
    {
        $string = self::str_to_unicode_word($str, '');
        return $string;
    }

    /**
     * _Pinyin
     * @param $_Num
     * @param $_Data
     * @return int|string
     */
    public static function _Pinyin($_Num, $_Data)
    {
        if ($_Num > 0 && $_Num < 160) {
            return chr($_Num);
        } elseif ($_Num < -20319 || $_Num > -10247) {
            return '';
        } else {
            foreach ($_Data as $k => $v) {
                if ($v <= $_Num) break;
            }
            return $k;
        }
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * @return string 拼接完成以后的字符串
     */
    public static function createLinkstring($para)
    {
        $arg = "";
        while (list ($key, $val) = each($para)) {
            $arg .= $key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg) - 2);

        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }

        return $arg;
    }

    /**
     * 格式化字符串数字输出
     * @param string $prefix
     * @param        $length
     * @param        $number
     * @return string
     */
    public static function formatNumberString($prefix = 'Lm',$length,$number){
        return $prefix.sprintf("%0".$length."d",$number);
    }

	/**
	 * 过滤所有的空白字符（空格、全角空格、换行等）
	 * @param $string
	 * @return mixed
	 */
	public static function variantTrim($string){
		$search  = array(" ", "　", "\n", "\r", "\t");
		$replace = array("", "", "", "", "");

		return str_replace($search, $replace, $string);
	}

	/**
	 * 生成token字符串
	 * @return string
	 */
	public static function generateToken(){
		$str = md5(uniqid(md5(microtime(true)),true));  //生成一个不会重复的字符串
		return sha1($str);  //加密
	}

}