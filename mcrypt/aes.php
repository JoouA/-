<?php
error_reporting(0);
class PubEncrypt
{
	public static $key  = '123454567890876453434';

	public static function AesEncrypt($data, $key = null) 
	{
		$data = trim($data);
		if($data == '') return '';

		try {
			if (! extension_loaded('mcrypt')) {
				throw new Exception('当前PHP环境没有加载mcrypt扩展');
			}

			// 打开加密算法和模式
			$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '' );

			$key = self::substr($key == null ? self::$key : $key, 0, mcrypt_enc_get_key_size($module));
            $iv = substr(md5($key), 0, mcrypt_enc_get_iv_size($module));	

             // 初始化加密
             mcrypt_generic_init($module, $key, $iv);
 
             // 加密数据
             $encrypt = mcrypt_generic($module, $data);
 
             // 反初始化释放资源
             mcrypt_generic_deinit($module);
 
             // 关闭资源对象退出
             mcrypt_module_close($module);
             return array('status' => true, 'data' => base64_encode($encrypt));

		} catch(Exception $e) {
			$error = $e->getMessage();
			$data = [
				'status' => false,
				'msg' => $error
			];

			return $data;
		}
	}


	public static function AesDecrypt($data, $key = null)
	{
		$data = trim($data);
		if ($data == '') {
			return '';
		}

		try {
			if (! extension_loaded('mcrypt')) {
				throw new Exception("当前PHP环境没有加载mcrypt扩展");
			}

			$data = base64_decode($data);
			$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
			$key = self::substr($key == null ? self::$key : $key, 0, mcrypt_enc_get_key_size($module));
			$iv = substr(md5($key), 0, mcrypt_enc_get_iv_size($module));
			mcrypt_generic_init($module, $key, $iv);
            $data = mdecrypt_generic($module, $encrypted);
            mcrypt_generic_deinit($module);
            mcrypt_module_close($module);

            return array('status' => true, 'data' => rtrim($data,"\0"));
		} catch(Exception $e) {
			$error = $e->getMessage();
            $data = array(
                 'status' => false,
                 'msg' => $error
             );
            return $data;
		}

	} 


	private static function substr($string, $start, $length)
	{
       return extension_loaded('mbstring') ? mb_substr($string, $start, $length, '8bit') : substr($string,$start,$length);
    }
}
$data = PubEncrypt::AesEncrypt('tangweitao');
print_r($data);
print_r(PubEncrypt::AesDecrypt($data));

