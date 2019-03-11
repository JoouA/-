<?php
class Crypt3Des
{
    public $key		= "ccsecCovision";
    public $iv 		= "12345678";
   
    //加密
    public function encrypt($input)
    {
        $input = $this->padding( $input );
        $key = $this->key;

        //使用MCRYPT_3DES算法,cbc模式
        $td = @mcrypt_module_open( MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
        //初始处理
        @mcrypt_generic_init($td, $key, $this->iv);
        //加密
        //var_dump($input);die;
        $data = @mcrypt_generic($td, $input);
        //结束
        @mcrypt_generic_deinit($td);
        @mcrypt_module_close($td);
        //$data = $this->removeBR(base64_encode($data));
        return base64_encode($data);
        //return $data;
    }
   
    //解密
    public function decrypt($encrypted)
    {
        $encrypted = base64_decode($encrypted);
        $key = $this->key;
        //使用MCRYPT_3DES算法,cbc模式
        $td = @mcrypt_module_open( MCRYPT_3DES,'',MCRYPT_MODE_ECB,'');
        //初始处理
        @mcrypt_generic_init($td, $key, $this->iv);
        //解密
        $decrypted = @mdecrypt_generic($td, $encrypted);
        //结束
        @mcrypt_generic_deinit($td);
        @mcrypt_module_close($td);
        //$decrypted = $this->removePadding($decrypted);
        return $decrypted;
    }


    //填充密码，填充至8的倍数
    public function padding( $str )
    {
        $len = 8 - strlen( $str ) % 8;
        for ( $i = 0; $i < $len; $i++ )
        {
            $str .= '#';
        }
        return $str ;
    }


    //删除填充符
    public function removePadding( $str )
    {
        $len = strlen( $str );
        $newstr = "";
        $str = str_split($str);
        for ($i = 0; $i < $len; $i++ )
        {
            if ($str[$i] != chr( 0 ))
            {
                $newstr .= $str[$i];
            }
        }
        return $newstr;
    }


    //删除回车和换行
    public function removeBR( $str )
    {
        $len = strlen( $str );
        $newstr = "";
        $str = str_split($str);
        for ($i = 0; $i < $len; $i++ )
        {
            if ($str[$i] != '\n' and $str[$i] != '\r')
            {
                $newstr .= $str[$i];
            }
        }
   
        return $newstr;
    }
}

$license_conf = file_get_contents('license.conf');
$crypt = new Crypt3Des();
$license_code;
$license_code = $crypt->encrypt($license_conf);
$license_code = file_put_contents('sys_license.dat', 'hsimp@' . $license_code);
// echo 'Decode:' . $crypt->decrypt($license_code);
echo 'License file created successfully!<br>';
echo '<a href="sys_license.dat">Click me to download it</a>';
