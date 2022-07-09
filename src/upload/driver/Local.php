<?php
namespace darkwarrior\easy\upload\driver;

use darkwarrior\easy\upload\exception\UploadException;

/**
 * class Local
 * 本地上传驱动
 * @Author   黑暗中的武者
 * @DateTime 2019-06-27T14:24:06+0800
 */
class Local
{
    /**
     * [$rootPath 上传文件根目录]
     * @var [type]
     */
    private $rootPath;

    /**
     * [__construct 构造函数，用于设置上传根路径]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T13:33:58+0800
     * @param    [type]                   $config [description]
     */
    public function __construct()
    {}

    /**
     * [checkRootPath 检测上传根目录]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T15:37:36+0800
     * @param    [type]                   $rootpath [根目录]
     * @return   [type]                             [true-检测通过，false-检测失败]
     */
    public function checkRootPath($rootPath)
    {
        if (!(is_dir($rootPath) && is_writable($rootPath))) {
            throw new UploadException("上传根目录不存在！请尝试手动创建：{$rootPath}");
        }
        // 设置根目录
        $this->rootPath = $rootPath;
        
        return true;
    }

    /**
     * [checkSavePath 检测上传目录]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T15:38:57+0800
     * @param    [type]                   $savePath [上传目录]
     * @return   [type]                             [检测结果，true-通过，false-失败]
     */
    public function checkSavePath($savePath)
    {
        // 检测并创建目录
        if (!$this->mkdir($savePath)) {
            throw new UploadException("创建目录{$savePath}失败！");
        } else {
            // 检测目录是否可写
            if (!is_writable($this->rootPath . $savePath)) {
                throw new UploadException("上传目录{$savePath}不可写！");
            } else {
                return true;
            }
        }
    }

    /**
     * [save 保存指定文件]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T16:59:19+0800
     * @param    [type]                   $file    [保存的文件信息]
     * @param    boolean                  $replace [同名文件是否覆盖]
     * @param    array                    $config  [上传配置参数]
     * @return   [type]                            [description]
     */
    public function save($file, $replace = true, $config = [])
    {
        // 文件路径
        $filename = $this->rootPath . $file['savepath'] . $file['savename'];

        // 不覆盖同名文件
        if (!$replace && is_file($filename)) {
            throw new UploadException("存在同名文件{$file['savename']}！");
        }

        // 移动文件
        if (!move_uploaded_file($file['tmp_name'], $filename)) {
            throw new UploadException("文件上传保存错误！");
        }

        // //添加水印
        // if ($config['is_water']) {
        //     $this->_water($filename);
        // }

        // //生成缩略图
        // if ($config['is_thumb']) {
        //     $this->_thumb($filename, $file, $config);
        // }

        //if($this->zipImags) {
            // TODO 对图片压缩包在线解压
        //}
        
        return true;
    }

    /**
     * [_thumb 生成缩略图]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T17:02:11+0800
     * @param    [type]                   $fileName [文件路径]
     * @param    [type]                   $file     [文件信息]
     * @param    [type]                   $config   [上传配置参数]
     * @return   [type]                             [description]
     */
    private function _thumb($fileName, $file, $config)
    {
        // 判断是不是图像文件 是图像文件进行缩略
        if (false !== getimagesize($fileName)) {
            // 缩略图最大宽度
            $thumbWidth     = explode(',', $config['thumbMaxWidth']);
            // 缩略图最大高度
            $thumbHeight    = explode(',', $config['thumbMaxHeight']);
            // 缩略图前缀
            $thumbPrefix    = explode(',', $config['thumb_prefix']);
            // 缩略图后缀
            $thumbSuffix    = explode(',', $config['thumb_suffix']);
            // 初始化图像处理类
            $Image          = new \Think\Image();
            // 生成图像缩略图 可多张
            for ($i = 0, $length = count($thumbWidth); $i < $length; $i++) {
                $prefix      =   isset($thumbPrefix[$i]) ? $thumbPrefix[$i] : $thumbPrefix[0]; //  前缀
                $suffix      =   isset($thumbSuffix[$i]) ? $thumbSuffix[$i] : $thumbSuffix[0]; // 后缀
                $thumb_name  =   $prefix . basename($fileName, '.' . $file['ext']) . $suffix;  // 缩略图名称
                // 生成缩略图  open打开元图像文件 thumb进行缩略 save保存缩略图
                $Image->open($fileName)->thumb($thumbWidth[$i], $thumbHeight[$i])->save(dirname($fileName) . '/' . $thumb_name . '.' . $file['ext']);
            }
        }
    }

    /**
     * [_water 添加水印]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T17:03:37+0800
     * @param    [type]                   $fileName [description]
     * @return   [type]                             [description]
     */
    private function _water($fileName)
    {
        //初始化图像处理类
        $Image = new \Think\Image();
        // 给原图添加水印并保存
        $Image->open($fileName)->water(C('WATER_PATH'))->save($fileName);
    }

    /**
     * [mkdir 创建目录]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T17:03:52+0800
     * @param    [type]                   $savePath [要创建的目录]
     * @return   [type]                             [创建状态，true-成功，false-失败]
     */
    public function mkdir($savePath)
    {
        $dir = $this->rootPath . $savePath;
        if (is_dir($dir)) {
            return true;
        }

        if (mkdir($dir, 0777, true)) {
            return true;
        } else {
            throw new UploadException("目录 {$savePath} 创建失败！");
        }
    }
}
