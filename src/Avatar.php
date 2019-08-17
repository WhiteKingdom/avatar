<?php

namespace Whiteki\Avatar;

use Illuminate\Config\Repository;

/**
 * Class Avatar
 * @package Whiteki\Avatar
 */
class  Avatar
{
    protected $config;

    /**
     * Avatar constructor.
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config->get('avatar');
    }

    /**
     * 生成图像
     * @param $name
     * @return false|resource
     */
    private function generate($name)
    {
        /**
         * 创建图层
         * @var  $img_res
         */
        $img_res = imagecreate($this->config['width'], $this->config['height']);
        $bg_color = imagecolorallocate($img_res, mt_rand(120, 190), mt_rant(120, 190), mt_rant(120, 190));
        $font_color = imagecolorallocate($img_res, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
        // 填充背景色
        imagefill($img_res, 1, 1, $bg_color);
        // 计算文字的宽高
        $pos = imagettfbbox($this->config['size'], 0, $this->config['font_file'], mb_substr($name, 0, 1));
        $font_width = $pos[2] - $pos[0] + 0.32 * $this->config['size'];
        $font_height = $pos[1] - $pos[5] + -0.16 * $this->config['size'];
        // 写入文字
        imagettftext($img_res, $this->config['size'], 0, ($this->config['width'] - $font_width) / 2, ($this->config['height'] - $font_height) / 2 + $font_height, $font_color, $this->config['font_file'], mb_substr($name, 0, 1));
        return $img_res;
    }

    /**
     * 输出图片
     * @param $name
     * @param bool $path
     */
    public function output($name, $path = false)
    {
        $img_res = this->$this->generate($name);
        $content_type = 'image/' . $this->config['type'];
        $generateMethodName='image'.$this->config['type'];
        // 确定是否输出到浏览器
        if(!$path)
        {
            header("Content-type: ".$content_type);
            $generateMethodName($img_res);
        }
        else {
            $generateMethodName($img_res,$path);
        }
        imagedestroy($img_res);
    }
}
