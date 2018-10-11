<?php

namespace Utils;


class ImageUtil
{

    /**
     * @param $path_2
     * @param string $path_1
     * @return array
     * @throws \Exception
     */
    public static function mergeImg($path_2, $path_1)
    {

        $path_2 = self::scalePic($path_2);
        $image_1 = imagecreatefrompng($path_1);
        $image_2 = imagecreatefromjpeg($path_2);
        $image_3 = imageCreatetruecolor(imagesx($image_1), imagesy($image_1));
        $color = imagecolorallocate($image_3, 255, 255, 255);
        imagefill($image_3, 0, 0, $color);
        imageColorTransparent($image_3, $color);
        imagecopyresampled($image_3, $image_1, 0, 0, 0, 0, imagesx($image_1), imagesy($image_1), imagesx($image_1), imagesy($image_1));
        imagecopymerge($image_3, $image_2, 480, 850, 0, 0, imagesx($image_2), imagesy($image_2), 100);
        //将画布保存到指定的gif文件
        $file_name = getcwd() . '/image/' . uniqid() . '.png';
        imagejpeg($image_3, $file_name);

        return [
            'localQr' => $path_2,
            'localRes' => $file_name
        ];


    }

    /**
     * @function 等比缩放函数(以保存的方式实现)
     * @param string $picname 被缩放的处理图片源
     * @param int $maxX 缩放后图片的最大宽度
     * @param int $maxY 缩放后图片的最大高度
     * @param string $pre 缩放后图片名的前缀名
     * @return string 返回后的图片名称(带路径),如a.jpg --> s_a.jpg
     */
    public static function scalePic($picname, $maxX = 200, $maxY = 200, $pre = 's_')
    {
        $info = getimagesize($picname); //获取图片的基本信息
        $width = $info[0];//获取宽度
        $height = $info[1];//获取高度
        //判断图片资源类型并创建对应图片资源
        $im = self::getPicType($info[2], $picname);
        //计算缩放比例
        $scale = ($maxX / $width) > ($maxY / $height) ? $maxY / $height : $maxX / $width;
        //计算缩放后的尺寸
        $sWidth = floor($width * $scale);
        $sHeight = floor($height * $scale);
        //创建目标图像资源
        $nim = imagecreatetruecolor($sWidth, $sHeight);
        //等比缩放
        imagecopyresampled($nim, $im, 0, 0, 0, 0, $sWidth, $sHeight, $width, $height);
        //输出图像
        $newPicName = self::outputImage($picname, $pre, $nim);
        //释放图片资源
        imagedestroy($im);
        imagedestroy($nim);
        unlink($picname);

        return $newPicName;
    }


    /**
     * function 判断并返回图片的类型(以资源方式返回)
     * @param int $type 图片类型
     * @param string $picname 图片名字
     * @return null|resource
     */
    public static function getPicType($type, $picname)
    {
        $im = null;
        switch($type){
            case 1:  //GIF
                $im = imagecreatefromgif($picname);
                break;
            case 2:  //JPG
                $im = imagecreatefromjpeg($picname);
                break;
            case 3:  //PNG
                $im = imagecreatefrompng($picname);
                break;
            case 4:  //BMP
                $im = imagecreatefromwbmp($picname);
                break;
            default:
                die("不认识图片类型");
                break;
        }

        return $im;
    }

    /**
     * function 输出图像
     * @param string $picname 图片名字
     * @param string $pre 新图片名前缀
     * @param resource $nim 要输出的图像资源
     * @return string
     */
    public static function outputImage($picname, $pre, $nim)
    {
        $info = getimagesize($picname);
        $picInfo = pathInfo($picname);
        $newPicName = $picInfo['dirname'] . '/' . $pre . $picInfo['basename'];//输出文件的路径
        switch($info[2]){
            case 1:
                imagegif($nim, $newPicName);
                break;
            case 2:
                imagejpeg($nim, $newPicName);
                break;
            case 3:
                imagepng($nim, $newPicName);
                break;
            case 4:
                imagewbmp($nim, $newPicName);
                break;
        }

        return $newPicName;
    }
}