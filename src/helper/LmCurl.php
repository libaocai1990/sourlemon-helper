<?php
namespace libaocai1990\SourLemonHelper\helper;

class LmCurl{
	/**
	 * @param $url
	 *
	 * @return mixed
	 */
	public static function curl_get($url) {
		$ch = curl_init();
		//设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 跳过证书检查
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
		//执行并获取HTML文档内容
		$output = curl_exec($ch);
		//释放curl句柄CURLOPT_SSL_VERIFYHOST
		curl_close($ch);

		return $output;
	}

	/**
	 * curl_post.
	 *
	 * @param $url
	 * @param $data
	 *
	 * @return bool|mixed
	 */
	public static function curl_post($url, $data) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);

		if (!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		if (curl_errno($curl)) {
			return false;
		}

		return $result;
	}

}