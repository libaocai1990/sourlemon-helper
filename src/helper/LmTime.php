<?php
namespace libaocai1990\SourLemonHelper\helper;

/**
 * Class LmTime
 * @package Api\Common\Core
 */
class LmTime
{

	/**
	 * 获取毫秒时间戳
	 */
	public static function msectime() {
		list($msec, $sec) = explode(' ', microtime());
		return (float) sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
	}

	/** 获取当前时间戳，精确到毫秒 */
	public static function microtime_float(){
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	/** 格式化时间戳，精确到毫秒，x代表毫秒 */
	public static function microtime_format($tag, $time)
	{
		list($usec, $sec) = explode(".", $time);
		$date = date($tag,$usec);
		return str_replace('x', $sec, $date);
	}

	/**
	 * 把秒数转换为时分秒的格式
	 * @param $times 时间，单位 秒
	 * @return string
	 */
	public static function secToTime($times){
		$result = '00:00:00';
		if ($times>0) {
			$hour   = floor($times / 3600);
			$minute = floor(($times - 3600 * $hour) / 60);
			$second = floor((($times - 3600 * $hour) - 60 * $minute) % 60);
			$result = $hour . ':' . $minute . ':' . $second;
		}
		return $result;
	}

    /**
     * 根据时间段显示不同的问候语
     * @param $name
     * @return string
     */
    public static function greetings($name){
        $hour = date("H");
        if($hour<6){
            $text = '又是一个不眠夜！';
        }
        else if($hour<9){
            $text = '新的一天开始了！';
        }
        else if($hour < 12){
            $text = '上午工作顺利吗？';
        }
        else if($hour < 14){
            $text = '中午好！吃了吗？';
        }
        else if($hour<17){
            $text = '下午好！别打盹哦';
        }
        else if($hour<19){
            $text = '傍晚好！下班了！';
        }
        else if($hour<22){
            $text = '晚上好！景色多美啊！';
        }
        else{
            $text = '夜深了，还不谁吗！';
        }

        return $name.','.$text;
    }

    /**
     * 获取当前时间unix时间戳
     * @return int
     */
    public static function time()
    {
        return time();
    }

    /**
     * 获取年月日时分秒
     * @return bool|string
     */
    public static function datetime()
    {
        return date('Y-m-d H:i:s', time());
    }

    /**
     * 格式化处理时间
     * @param        $time
     * @param string $format
     * @return bool|string
     */
    public static function format($time, $format = 'Y-m-d H:i:s')
    {
        return date($format, $time);
    }


    /**
     * 友好的时间显示
     *
     * @param int $sTime   待显示的时间
     * @param string $type 类型. normal | mohu | full | ymd | other
     * @param string $alt  已失效
     * @return string
     */
    public static function friendlyDate($sTime, $type = 'normal', $alt = 'false')
    {
        if (!$sTime)
            return '';
        //sTime=源时间，cTime=当前时间，dTime=时间差
        $cTime = time();
        $dTime = $cTime - $sTime;
        $dDay  = intval(date("z", $cTime)) - intval(date("z", $sTime));
        //$dDay     =   intval($dTime/3600/24);
        $dYear = intval(date("Y", $cTime)) - intval(date("Y", $sTime));
        //normal：n秒前，n分钟前，n小时前，日期
        if ($type == 'normal') {
            if ($dTime < 60) {
                if ($dTime < 10) {
                    return '刚刚';    //by yangjs
                } else {
                    return intval(floor($dTime / 10) * 10) . "秒前";
                }
            } elseif ($dTime < 3600) {
                return intval($dTime / 60) . "分钟前";
                //今天的数据.年份相同.日期相同.
            } elseif ($dYear == 0 && $dDay == 0) {
                //return intval($dTime/3600)."小时前";
                return '今天' . date('H:i', $sTime);
            } elseif ($dYear == 0) {
                return date("m月d日 H:i", $sTime);
            } else {
                return date("Y-m-d H:i", $sTime);
            }
        } elseif ($type == 'mohu') {
            if ($dTime < 60) {
                return $dTime . "秒前";
            } elseif ($dTime < 3600) {
                return intval($dTime / 60) . "分钟前";
            } elseif ($dTime >= 3600 && $dDay == 0) {
                return intval($dTime / 3600) . "小时前";
            } elseif ($dDay > 0 && $dDay <= 7) {
                return intval($dDay) . "天前";
            } elseif ($dDay > 7 && $dDay <= 30) {
                return intval($dDay / 7) . '周前';
            } elseif ($dDay > 30) {
                return intval($dDay / 30) . '个月前';
            }
            //full: Y-m-d , H:i:s
        } elseif ($type == 'full') {
            return date("Y-m-d , H:i:s", $sTime);
        } elseif ($type == 'ymd') {
            return date("Y-m-d", $sTime);
        } else {
            if ($dTime < 60) {
                return $dTime . "秒前";
            } elseif ($dTime < 3600) {
                return intval($dTime / 60) . "分钟前";
            } elseif ($dTime >= 3600 && $dDay == 0) {
                return intval($dTime / 3600) . "小时前";
            } elseif ($dYear == 0) {
                return date("Y-m-d H:i:s", $sTime);
            } else {
                return date("Y-m-d H:i:s", $sTime);
            }
        }
    }


    /**
     * 计算距离某指定时间还剩于几天几时几分几秒
     * @param $time
     * @return string
     */
    public static function remainTime($time, $type = '2')
    {
        $second = $time - time();
        $day    = floor($second / (3600 * 24));
        $second = $second % (3600 * 24);//除去整天之后剩余的时间
        $hour   = floor($second / 3600);
        $second = $second % 3600;//除去整小时之后剩余的时间
        $minute = floor($second / 60);
        $second = $second % 60;//除去整分钟之后剩余的时间
        //返回字符串
        switch ($type) {
            case '1':
                $str = $day . '天' . $hour . '小时' . $minute . '分' . $second . '秒';
                break;
            case '2':
                $str = $hour . ':' . $minute . ':' . $second;
                break;
        }
        return $str;
    }

    /**
     * 转换时间函数tranTime
     * @param $time
     * @param bool $flag
     * @return bool|string
     */
    public static function tranTime($time, $flag = false)
    {
        if ($flag == true) {
            $rtime = date("m月d日", $time);
        } else {
            $rtime = date("m月d日H:i", $time);
        }
        $htime = date("H:i", $time);

        $time = time() - $time;

        if ($time < 60) {
            $str = '刚刚';
        } elseif ($time < 60 * 60) {
            $min = floor($time / 60);
            $str = $min . '分钟前';
        } elseif ($time < 60 * 60 * 24) {
            $h   = floor($time / (60 * 60));
            $str = $h . '小时前 ';
        } elseif ($time < 60 * 60 * 24 * 3) {
            $d = floor($time / (60 * 60 * 24));
            if ($d == 1)
                $str = '昨天 ' . $htime;
            else
                $str = '前天 ' . $htime;
        } else {
            $str = $rtime;
        }
        return $str;
    }

    /**
     * 获取本月最后一天
     * @param $month
     * @param $year
     * @return bool|string
     */
    public static function getMonthLastDay($month, $year)
    {
        $nextMonth = $month + 1;
        $year      = ($nextMonth > 12) ? ($year + 1) : $year;
        $lastDay   = date('d', mktime(0, 0, 0, $nextMonth, 0, $year));
        return $lastDay;
    }

    /**
     * 获取GMTime
     * @return int
     */
    public static function get_gmtime()
    {
        return (time() - date('Z'));
    }

}