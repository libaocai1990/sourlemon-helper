<?php

namespace libaocai1990\SourLemonHelper;

/**
 * 验证处理类函数库
 * Class Validate
 */
class Validate
{
	/**
	 * 邮件格式验证的函数
	 * @param $email
	 * @return bool
	 */
	public static function check_email($email)
	{
		if (!preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/", $email)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 检查字符串是否是邮箱
	 * @param $string
	 * @return bool
	 */
	public static function check_is_email($string)
	{
		//用户名：以数字、字母、下滑线组成；
		if (ereg("^[a-zA-Z][a-zA-Z0-9_]{3,9}@[0-9a-zA-Z]{1,10}(\.)(com|cn|com.cn|net)$", $string)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 验证手机号码
	 * @param $mobile
	 * @return bool
	 */
	public static function check_mobile($mobile)
	{
		if (!empty($mobile) && !preg_match("/^(\+)?\d+$/", $mobile)) {
			return false;
		} else {
			return true;
		}

	}

	/**
	 * 验证身份证
	 * @param $idcard
	 * @return bool
	 */
	public static function check_idcard($idcard)
	{
		if (!empty($idcard) && !preg_match("/(^\d{15}$)|(^\d{17}([0-9]|X)$)/", $idcard)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 检查是否是身份证号
	 * @param $number
	 * @return bool
	 */
	public static function is_idcard_number($number)
	{
		// 转化为大写，如出现x
		$number = strtoupper($number);
		//加权因子
		$wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		//校验码串
		$ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
		//按顺序循环处理前17位
		$sigma = 0;
		for ($i = 0; $i < 17; $i++) {
			//提取前17位的其中一位，并将变量类型转为实数
			$b = (int)$number{$i};

			//提取相应的加权因子
			$w = $wi[$i];

			//把从身份证号码中提取的一位数字和加权因子相乘，并累加
			$sigma += $b * $w;
		}
		//计算序号
		$snumber = $sigma % 11;

		//按照序号从校验码串中提取相应的字符。
		$check_number = $ai[$snumber];

		if ($number{17} == $check_number) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 根据身份证号，自动返回性别
	 * @param $cid
	 * @return string
	 */
	public static function get_sex_by_idcard($cid)
	{
		if (!self::isIdCard($cid)) return '';
		$sexint = (int)substr($cid, 16, 1);
		return $sexint % 2 === 0 ? '女' : '男';
	}

	/**
	 * 根据身份证号，自动返回对应的星座
	 * @param $cid
	 * @return string
	 */
	public static function get_constellation_by_idcard($cid)
	{
		if (!self::is_idcard($cid)) return '';
		$bir      = substr($cid, 10, 4);
		$month    = (int)substr($bir, 0, 2);
		$day      = (int)substr($bir, 2);
		$strValue = '';
		if (($month == 1 && $day <= 21) || ($month == 2 && $day <= 19)) {
			$strValue = "水瓶座";
		} else if (($month == 2 && $day > 20) || ($month == 3 && $day <= 20)) {
			$strValue = "双鱼座";
		} else if (($month == 3 && $day > 20) || ($month == 4 && $day <= 20)) {
			$strValue = "白羊座";
		} else if (($month == 4 && $day > 20) || ($month == 5 && $day <= 21)) {
			$strValue = "金牛座";
		} else if (($month == 5 && $day > 21) || ($month == 6 && $day <= 21)) {
			$strValue = "双子座";
		} else if (($month == 6 && $day > 21) || ($month == 7 && $day <= 22)) {
			$strValue = "巨蟹座";
		} else if (($month == 7 && $day > 22) || ($month == 8 && $day <= 23)) {
			$strValue = "狮子座";
		} else if (($month == 8 && $day > 23) || ($month == 9 && $day <= 23)) {
			$strValue = "处女座";
		} else if (($month == 9 && $day > 23) || ($month == 10 && $day <= 23)) {
			$strValue = "天秤座";
		} else if (($month == 10 && $day > 23) || ($month == 11 && $day <= 22)) {
			$strValue = "天蝎座";
		} else if (($month == 11 && $day > 22) || ($month == 12 && $day <= 21)) {
			$strValue = "射手座";
		} else if (($month == 12 && $day > 21) || ($month == 1 && $day <= 20)) {
			$strValue = "魔羯座";
		}
		return $strValue;
	}

	/**
	 * 根据身份证号，自动返回对应的生肖
	 * @param $cid
	 * @return string
	 */
	public static function get_shengxiao_by_idcard($cid)
	{
		if (!self::is_idcard($cid)) return '';
		$start = 1901;
		$end   = $end = (int)substr($cid, 6, 4);
		$x     = ($start - $end) % 12;
		$value = "";
		if ($x == 1 || $x == -11) {
			$value = "鼠";
		}
		if ($x == 0) {
			$value = "牛";
		}
		if ($x == 11 || $x == -1) {
			$value = "虎";
		}
		if ($x == 10 || $x == -2) {
			$value = "兔";
		}
		if ($x == 9 || $x == -3) {
			$value = "龙";
		}
		if ($x == 8 || $x == -4) {
			$value = "蛇";
		}
		if ($x == 7 || $x == -5) {
			$value = "马";
		}
		if ($x == 6 || $x == -6) {
			$value = "羊";
		}
		if ($x == 5 || $x == -7) {
			$value = "猴";
		}
		if ($x == 4 || $x == -8) {
			$value = "鸡";
		}
		if ($x == 3 || $x == -9) {
			$value = "狗";
		}
		if ($x == 2 || $x == -10) {
			$value = "猪";
		}
		return $value;
	}

	/**
	 * 根据身份证获取生日
	 * @param $idcard
	 * @return array|bool
	 */
	public static function get_birthday_by_idcard($idcard)
	{
		$result = array();
		if (!self::is_idcard($idcard)) return false;

		if (strlen($idcard) == 18) {
			$tyear  = intval(substr($idcard, 6, 4));
			$tmonth = intval(substr($idcard, 10, 2));
			$tday   = intval(substr($idcard, 12, 2));
		} elseif (strlen($idcard) == 15) {
			$tyear  = intval("19" . substr($idcard, 6, 2));
			$tmonth = intval(substr($idcard, 8, 2));
			$tday   = intval(substr($idcard, 10, 2));
		}
		if ($tyear > date("Y") || $tyear < (date("Y") - 100)) {
			$result['flag'] = 0;
		} elseif ($tmonth < 0 || $tmonth > 12) {
			$result['flag'] = 0;
		} elseif ($tday < 0 || $tday > 31) {
			$result['flag'] = 0;
		} else {
			if ((time() - mktime(0, 0, 0, $tmonth, $tday, $tyear)) > 18 * 365 * 24 * 60 * 60) {
				$result['flag'] = 0;
			} else {
				$result['flag'] = 1;
			}
		}
		$tdate           = $tyear . "-" . $tmonth . "-" . $tday;
		$result['tdate'] = $tyear . "-" . $tmonth . "-" . $tday;    //生日

		return $result;
	}

	/**
	 * 验证URL
	 * @param $url
	 * @return bool
	 */
	public static function check_url($url)
	{
		if (!empty($url) && !preg_match("^[A-Za-z]+://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$", $url)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 验证QQ
	 * @param $qq
	 * @return bool
	 */
	public static function check_qq($qq)
	{
		if (!empty($qq) && !preg_match("/(^[1-9][0-9]{4,}$)/", $qq)) {
			return false;
		} else {
			return true;
		}
	}
}











