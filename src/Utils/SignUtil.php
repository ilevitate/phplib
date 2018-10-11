<?php

namespace Utils;

class SignUtil
{
    /**
     * var string $method 加解密方法，可通过openssl_get_cipher_methods()获得
     */
    const METHOD = 'AES-128-ECB';

    /**
     * var string $secret_key 加解密的密钥
     */
    const KEY='ESGl4MCsKeoKCjs0eo6NE3ZQ5/B1AyXA4pVZ3dpsPlg=';

    /**
     * var string $iv 加解密的向量，有些方法需要设置比如CBC
     */
    const IV = '';

    /**
     * var string $options （不知道怎么解释，目前设置为0没什么问题）
     */
    const OPTION = 0 ;

    /**
     * 加密方法，对数据进行加密，返回加密后的数据
     *
     * @param string $data 要加密的数据
     *
     * @return string
     *
     */
    public static function encrypt($data)
    {
        return openssl_encrypt($data, self::METHOD, self::KEY, self::OPTION, self::IV);
    }

    /**
     * 解密方法，对数据进行解密，返回解密后的数据
     *
     * @param string $data 要解密的数据
     *
     * @return string
     *
     */
    public static function decrypt($data)
    {
        return openssl_decrypt($data, self::METHOD, self::KEY, self::OPTION, self::IV);
    }


    //加密函数
    public static function lock_url($txt, $key = 'www.jb51.net')
    {
        $txt = $txt . $key;
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
        $nh = rand(0, 64);
        $ch = $chars[$nh];
        $mdKey = md5($key . $ch);
        $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
        $txt = base64_encode($txt);
        $tmp = '';
        $i = 0;
        $j = 0;
        $k = 0;
        for ($i = 0; $i < strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = ($nh + strpos($chars, $txt[$i]) + ord($mdKey[$k++])) % 64;
            $tmp .= $chars[$j];
        }
        return urlencode(base64_encode($ch . $tmp));
    }

//解密函数
    public static function unlock_url($txt, $key = 'www.jb51.net')
    {
        $txt = base64_decode(urldecode($txt));
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
        $ch = $txt[0];
        $nh = strpos($chars, $ch);
        $mdKey = md5($key . $ch);
        $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
        $txt = substr($txt, 1);
        $tmp = '';
        $i = 0;
        $j = 0;
        $k = 0;
        for ($i = 0; $i < strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = strpos($chars, $txt[$i]) - $nh - ord($mdKey[$k++]);
            while ($j < 0) $j += 64;
            $tmp .= $chars[$j];
        }
        return trim(base64_decode($tmp), $key);
    }
}