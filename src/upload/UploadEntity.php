<?php
namespace warrior\easy\upload;

/**
 * class UploadEntity
 * 上传参数对象类
 * @author 黑暗中的武者
 */
class UploadEntity
{
    /**
     * [$uploadConfig 上传配置参数]
     * @var array
     */
    private $uploadConfig = [];

    /**
     * [$driverConfig 驱动配置参数]
     * @var array
     */
    private $driverConfig = [];

    /**
     * [setUploadConfig 设置上传配置参数]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T14:02:24+0800
     * @param    string                   $fileType [文件类型]
     * @param    [type]                   $config   [自定义上传配置参数]
     */
    public function setUploadConfig($config= [])
    {
        $this->uploadConfig = $config;
    }

    /**
     * [getUploadConfig 获取上传配置参数]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-28T19:28:19+0800
     * @return   [type]                   [description]
     */
    public function getUploadConfig()
    {
        return $this->uploadConfig;
    }

    /**
     * [setDriverConfig 设置驱动配置参数]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T14:08:11+0800
     * @param    string                   $driver [description]
     * @param    [type]                   $config [description]
     */
    public function setDriverConfig($config = [])
    {
        $this->driverConfig = $config;
    }

    /**
     * [getDriverConfig 获取驱动配置参数]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-28T19:28:26+0800
     * @return   [type]                   [description]
     */
    public function getDriverConfig()
    {
        return $this->driverConfig;
    }
}
