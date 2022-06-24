<?php
namespace warrior\easy\upload;

use warrior\upload\exception\UploadException;

/**
 * class UploadClient
 * 文件上传
 * @author   黑暗中的武者
 */
class UploadClient
{
    /**
     * [$uploadEntity 上传参数对象实体]
     * @var [type]
     */
    private $uploadEntity;

    /**
     * [__construct 构造]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-28T19:37:12+0800
     * @param    uploadEntity                   $uploadEntity [description]
     */
    public function __construct()
    {}

    /**
     * [setUploadEntity description]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T14:38:53+0800
     * @param    [type]                   $value [description]
     */
    public function setUploadEntity($value)
    {
        $this->uploadEntity = $value;
    }

    /**
     * [upload 上传图片]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-28T19:41:51+0800
     * @return   [type]                   [description]
     */
    public function upload()
    {
        // 实例化上传适配器
        $Upload = new \warrior\easy\upload\Upload(
            $this->uploadEntity->getUploadConfig(),
            $this->uploadEntity->getDriverConfig()
        );

        // 执行上传
        $info = $Upload->upload();

        return $info;
        
        // //判断是否上传成功
        // if($info && is_array($info)) {
        //     //文件上传成功，返回文件信息
        //     // [
        //     //     "fileData" => [
        //     //         "name" => "TIM截图20190611193527.png",
        //     //         "type" => "image/png",
        //     //         "size" => 64378,
        //     //         "key"  => "fileData",
        //     //         "ext"  => "png",
        //     //         "md5"  => "b0d0bceecb10bfe40b07f3ec6fb5551b",
        //     //         "sha1" => "c938c3928c182237e084ec0571e93b0ddbe124f6",
        //     //         "savename" => "5d173807c1034.png",
        //     //         "savepath" => "apk/2019-06-29/",
        //     //         "thumb_prefix"   :   "",
        //     //         "thumb_suffix"   :   "",
        //     //     ]
        //     // ]
    }
}
