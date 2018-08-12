<?php
namespace libaocai1990\SourLemonHelper\helper;

/**
 * 数组辅助函数
 * Class LmArray
 * @package libaocai1990\SourLemonHelper
 */
class LmArray
{

	/**
	 * 二维数组根据字段进行排序
	 * @params array $array 需要排序的数组
	 * @params string $field 排序的字段
	 * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
	 */
	public static function arraySequence($array, $field, $sort = 'SORT_DESC')
	{
		$arrSort = array();
		foreach ($array as $uniqid => $row) {
			foreach ($row as $key => $value) {
				$arrSort[$key][$uniqid] = $value;
			}
		}
		array_multisort($arrSort[$field], constant($sort), $array);
		return $array;
	}

	/**
	 * 二维数组根据指定field排序
	 * @param $arr
	 * @param $field
	 * @param string $sort
	 * @return bool
	 */
	public static function arrayFieldSort($arr,$field, $sort = 'SORT_DESC'){
		$arr1 = array_column($arr,$field);
		array_multisort($arr1, constant($sort),$arr);

		return $arr;
	}

	/**
	 * 二维数组去掉重复的子项
	 * @param $data
	 * @return array
	 */
	public static function arrayUnique($data){
		return array_unique($data, SORT_REGULAR);
	}

	/**
	 * 因为某一键名的值不能重复，删除重复项
	 * @param $arr
	 * @param $key
	 * @return mixed
	 */
	public static function assoc_unique($arr, $key) {
		$tmp_arr = array();
		foreach ($arr as $k => $v) {
			if (in_array($v[$key], $tmp_arr)) {//搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
				unset($arr[$k]);
			} else {
				$tmp_arr[] = $v[$key];
			}
		}
		sort($arr); //sort函数对数组进行排序
		return $arr;
	}

	/**
	 * 因内部的一维数组不能完全相同，而删除重复项
	 * @param $array2D
	 * @return array
	 */
	public static function array_unique_fb($array2D) {
		foreach ($array2D as $v) {
			$v = join(",", $v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
			$temp[] = $v;
		}
		$temp = array_unique($temp);//去掉重复的字符串,也就是重复的一维数组
		foreach ($temp as $k => $v) {
			$temp[$k] = explode(",", $v);//再将拆开的数组重新组装
		}
		return $temp;
	}

    /**
     * element
     * @param $item
     * @param $array
     * @param null $default
     * @return null
     */
    public static function element($item, $array, $default = NULL)
    {
        return array_key_exists($item, $array) ? $array[$item] : $default;
    }

    /**
     * Random Element
     * @param $array
     * @return mixed
     */
    public static function random_element($array)
    {
        return is_array($array) ? $array[array_rand($array)] : $array;
    }

    /**
     * Elements
     * @param $items
     * @param $array
     * @param null $default
     * @return array
     */
    public static function elements($items, $array, $default = NULL)
    {
        $return = array();

        is_array($items) OR $items = array($items);

        foreach ($items as $item) {
            $return[$item] = array_key_exists($item, $array) ? $array[$item] : $default;
        }

        return $return;
    }

    /**
     * _Array_Combine
     * @param $_Arr1
     * @param $_Arr2
     * @return array
     */
    public static function _Array_Combine($_Arr1, $_Arr2)
    {
        $_Res  = array();
        for($i=0; $i<count($_Arr1); $i++) {
            $_Res[$_Arr1[$i]] = $_Arr2[$i];
        }
        return $_Res;
    }
}