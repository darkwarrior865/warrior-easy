<?php
namespace warrior\easy\upload;

use warrior\easy\upload\exception\UploadException;

/**
 * class Upload
 * 文件上传核心类
 * @author   黑暗中的武者
 */
class Upload
{
    /**
     * [$uploader 上传驱动实例]
     * @var [type]
     */
    private $uploader;

    /**
     * [$config 默认上传配置]
     * @var [type]
     */
    private $config = [
        'mimes'             =>  [],                   // 允许上传的文件MiMe类型
        'max_size'          =>  0,                    // 上传的文件大小限制 (0-不做限制)
        'exts'              =>  [],                   // 允许上传的文件后缀
        'auto_sub'          =>  true,                 // 自动子目录保存文件
        'sub_name'          =>  ['date', 'Y-m-d'],    // 子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'root_path'         =>  './uploads/',         // 保存根路径
        'save_path'         =>  '',                   // 保存路径
        'save_name'         =>  ['uniqid', ''],       // 上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'save_ext'          =>  '',                   // 文件保存后缀，空则使用原后缀
        'replace'           =>  false,                // 存在同名是否覆盖
        'hash'              =>  true,                 // 是否生成hash编码
        'callback'          =>  false,                // 检测文件是否存在回调，如果存在返回文件信息数组
        'driver'            =>  'local',              // 文件上传驱动
        'is_thumb'          =>  false,                // 是否对上传图片进行缩略图处理
        'thumb_max_width'   =>  '',                   // 缩略图最大宽度  可设置多个逗号隔开  例：'100,100'
        'thumb_max_height'  =>  '',                   // 缩略图最大高度  可设置多个逗号隔开  例：'100,100'
        'thumb_prefix'      =>  'thumb_',             // 缩略图前缀   可设置多个逗号隔开  例：'thumb_,thumb_1_'
        'thumb_suffix'      =>  '',                   // 缩略图后缀  可设置多个逗号隔开  例：'_1,_2'
        'is_water'          =>  false,                // 是否添加水印
        'water_path'        =>  '',                   // 水印图片路径
    ];

    /**
     * [$driverConfig 默认驱动配置]
     * @var array
     */
    private $driverConfig = [];

    /**
     * [__construct 构造方法，用于构造上传实例]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T15:25:19+0800
     * @param    array                    $uploadConfig [上传配置参数]
     * @param    array                   $driverConfig [驱动配置参数]
     */
    public function __construct($uploadConfig = [], $driverConfig = [])
    {
        // 获取配置 合并本类基础配置和自定义配置
        $this->config       = array_merge($this->config, $uploadConfig);
        // 获取配置 合并本类基础驱动配置和自定义驱动配置
        $this->driverConfig = array_merge($this->driverConfig, $driverConfig);

        // 设置上传驱动
        $this->setDriver($this->config['driver'], $this->driverConfig);

        // 调整配置，把字符串配置参数转换为数组
        if (!empty($this->config['mimes'])) { // 文件MiMe类型
            // 如果是字符串 转换为数组
            if (is_string($this->config['mimes'])) {
                $this->config['mimes'] = explode(',', $this->config['mimes']);
            }
            // 将数组每个元素处理成小写
            $this->config['mimes'] = array_map('strtolower', $this->config['mimes']);
        }
        if (!empty($this->config['exts'])) { // 文件后缀
            if (is_string($this->config['exts'])) {
                $this->config['exts'] = explode(',', $this->config['exts']);
            }
            $this->config['exts'] = array_map('strtolower', $this->config['exts']);
        }
    }

    /**
     * [__get 使用 $this->name 获取配置]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T15:30:01+0800
     * @param    [type]                   $name [配置名称]
     * @return   [type]                         [配置值]
     */
    public function __get($name)
    {
        return $this->config[$name];
    }

    /**
     * [__set 使用 $this->name = value 设置配置参数]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T15:30:23+0800
     * @param    [type]                   $name  [配置名称]
     * @param    [type]                   $value [配置值]
     */
    public function __set($name, $value)
    {
        // 判断是否已存在
        if (isset($this->config[$name])) {
            $this->config[$name] = $value;
            if ($name == 'driver_config') {
                //改变驱动配置后重置上传驱动
                //注意：必须选改变驱动然后再改变驱动配置
                $this->setDriver(); 
            }
        }
    }

    public function __isset($name)
    {
        return isset($this->config[$name]);
    }

    /**
     * [upload 上传文件]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T15:34:52+0800
     * @param    string                   $files [文件信息数组 $files ，通常是 $_FILES 数组]
     * @return   [type]                          [上传信息]
     */
    public function upload($files = '')
    {
        // 如果没有传值 则自动获取 _FILES
        if ('' === $files) {
            $files = $_FILES;
        }
        // 如果传过来的文件为空 则返回错误
        if (empty($files)) {
            throw new UploadException("没有上传的文件！");
        }

        // 检测上传根目录是否可用
        $this->uploader->checkRootPath($this->config['root_path']);

        // 检查上传目录是否可用
        $this->uploader->checkSavePath($this->config['save_path']);

        // 逐个检测并上传文件
        $info = [];

        // 返回 mime 类型
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
        }

        // 对上传文件数组信息处理
        $files = $this->dealFiles($files);

        foreach ($files as $key => $file) {
            // 过滤html
            $file['name'] = strip_tags($file['name']);

            if (!isset($file['key'])) {
                $file['key'] = $key;
            }

            // 通过扩展获取文件类型，可解决FLASH上传$FILES数组返回文件类型错误的问题
            if (isset($finfo)) {
                 // 获取指定文件的 mime 类型
                $file['mine'] = finfo_file( $finfo, $file['tmp_name']);
            }

            // 获取上传文件后缀，允许上传无后缀文件
            $file['ext'] = pathinfo($file['name'], PATHINFO_EXTENSION);

            // 文件上传检测
            if (!$this->check($file)) {
                continue;
            }

            // 获取文件hash
            if ($this->config['hash']) {
                $file['md5']  = md5_file($file['tmp_name']);
                $file['sha1'] = sha1_file($file['tmp_name']);
            }

            // 调用回调函数检测文件是否存在
            // $data = call_user_func($this->config['callback'], $file);
            // if($this->config['callback'] && $data) {
            //     if(file_exists('.'.$data['path'])) {
            //         $info[$key] = $data;
            //         continue;
            //     } elseif ($this->config['remove_trash']) {
            //         call_user_func($this->config['remove_trash'],$data);//删除垃圾据
            //     }
            // }

            // 生成保存文件名
            $savename = $this->getSaveName($file);
            if (false == $savename) {
                continue;
            } else {
                $file['savename'] = $savename;
            }

            // 检测并创建子目录 一般以日期命名
            $subpath = $this->getSubPath($file['name']);
            if (false === $subpath) {
                continue;
            } else {
                $file['savepath'] = $this->config['save_path'] . $subpath;
            }

            // 对图像文件进行严格检测
            $ext = strtolower($file['ext']);
            if (in_array($ext, ['gif','jpg','jpeg','bmp','png','swf'])) {
                $imginfo = getimagesize($file['tmp_name']);
                if (empty($imginfo) || ($ext == 'gif' && empty($imginfo['bits']))) {
                    throw new UploadException("非法图像文件！");
                }
            }

            // 保存文件 并记录保存成功的文件
            if ($this->uploader->save($file, $this->config['replace'], $this->config)) {
                unset($file['error'], $file['tmp_name']);
                // 如果需要生成缩略图 返回前后缀
                // if ($this->config['is_thumb']) {
                //     $file['thumb_prefix'] = $this->config['thumb_prefix'];
                //     $file['thumb_suffix'] = $this->config['thumb_suffix'];
                // }
                $info[$key] = $file;
            }
        }
        if (isset($finfo)) {
            finfo_close($finfo);
        }
        return empty($info) ? false : $info;
    }

    /**
     * [dealFiles 转换上传文件数组变量为正确的方式]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T15:48:15+0800
     * @param    [type]                   $files [上传的文件变量]
     * @return   [type]                          [文件数组]
     */
    private function dealFiles($files)
    {
        $fileArray  = [];
        $n          = 0;
        foreach ($files as $key => $file) {
            if (is_array($file['name'])) {
                $keys       =   array_keys($file);
                $count      =   count($file['name']);
                for ($i = 0; $i < $count; $i++) {
                    $fileArray[$n]['key'] = $key;
                    foreach ($keys as $_key) {
                        $fileArray[$n][$_key] = $file[$_key][$i];
                    }
                    $n++;
                }
            } else {
               $fileArray = $files;
               break;
            }
        }
       return $fileArray;
    }

    /**
     * [setDriver 设置上传驱动]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T16:19:26+0800
     * @param    [type]                   $driver [description]
     * @param    [type]                   $config [description]
     */
    private function setDriver($driver = null, $driverConfig = null)
    {
        $driver        = $driver ? : 'local';
        
        $driver_config = $driverConfig ? : [];
        
        $class  = strpos($driver, '\\') ? $driver : 'warrior\\easy\\upload\\driver\\' . ucfirst(strtolower($driver));

        if (!class_exists($class)) {
            throw new UploadException("不存在上传驱动：{$driver}");
        }
        
        $this->uploader = new $class($driver_config);
    }

    /**
     * [check 检查上传的文件]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T15:54:34+0800
     * @param    [type]                   $file [文件信息]
     * @return   [type]                         [description]
     */
    private function check($file)
    {
        // 文件上传失败，捕获错误代码
        if ($file['error']) {
            $this->error($file['error']);
        }

        // 无效上传
        if (empty($file['name'])) {
            throw new UploadException("未知上传错误！");
        }

        // 检查是否合法上传
        if (!is_uploaded_file($file['tmp_name'])) {
            throw new UploadException("非法上传文件！");
        }

        // 检查文件大小
        if (!$this->checkSize($file['size'])) {
            throw new UploadException("上传文件大小不符！");
        }

        // 检查文件Mime类型
        // FLASH上传的文件获取到的mime类型都为application/octet-stream
        if (!$this->checkMime($file['mine'])) {
            throw new UploadException("上传文件MIME类型不允许！");
        }

        // 检查文件后缀
        if (!$this->checkExt($file['ext'])) {
            throw new UploadException("上传文件后缀不允许！");
        }

        return true;
    }

    /**
     * [error 获取错误代码信息]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T16:17:21+0800
     * @param    [type]                   $errorNo [description]
     * @return   [type]                            [description]
     */
    private function error($errorNo)
    {
        switch ($errorNo) {
            case 1:
                throw new UploadException("上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值！");
                break;
            case 2:
                throw new UploadException("上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值！");
                break;
            case 3:
                throw new UploadException("文件只有部分被上传！");
                break;
            case 4:
                throw new UploadException("没有文件被上传！");
                break;
            case 6:
                throw new UploadException("找不到临时文件夹！");
                break;
            case 7:
                throw new UploadException("文件写入失败！");
                break;
            default:
                throw new UploadException("未知上传错误！");
        }
    }

    /**
     * [checkSize 检查文件大小是否合法]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T15:55:15+0800
     * @param    [type]                   $size [description]
     * @return   [type]                         [description]
     */
    private function checkSize($size)
    {
        return !($size > $this->config['max_size']) || (0 == $this->config['max_size']);
    }

    /**
     * [checkMime 检查上传的文件MIME类型是否合法]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T15:56:08+0800
     * @param    [type]                   $mime [description]
     * @return   [type]                         [description]
     */
    private function checkMime($mime)
    {
        return empty($this->config['mimes']) ? true : in_array(strtolower($mime), $this->config['mimes']);
    }

    /**
     * [checkExt 检查上传的文件后缀是否合法]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T15:56:27+0800
     * @param    [type]                   $ext [description]
     * @return   [type]                        [description]
     */
    private function checkExt($ext)
    {
        return empty($this->config['exts']) ? true : in_array(strtolower($ext), $this->config['exts']);
    }

    /**
     * [getSaveName 根据上传文件命名规则取得保存文件名]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T15:56:41+0800
     * @param    [type]                   $file [文件信息]
     * @return   [type]                         [description]
     */
    private function getSaveName($file)
    {
        // 命名规则
        $rule = $this->config['save_name'];
        // 没有命名规则 保持文件名不变
        if (empty($rule)) {
            // 解决pathinfo中文文件名BUG
            $filename = substr(pathinfo("_{$file['name']}", PATHINFO_FILENAME), 1);
            $savename = $filename;
        } else {
            $savename = $this->getName($rule, $file['name']);
            if (empty($savename)) {
                throw new UploadException("文件命名规则错误！");
            }
        }

        // 文件保存后缀，支持强制更改文件后缀
        $ext = empty($this->config['save_ext']) ? $file['ext'] : $this->config['save_ext'];

        return $savename . '.' . $ext;
    }

    /**
     * [getSubPath 获取子目录的名称]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T16:01:37+0800
     * @param    [type]                   $filename [description]
     * @return   [type]                             [description]
     */
    private function getSubPath($filename)
    {
        $subpath = '';
        $rule    = $this->config['sub_name']; // 自动生成规则
        // 如果允许自动生成 并且存在生成规则的话则 自动生成子目录
        if ($this->config['auto_sub'] && !empty($rule)) {
            // 生成子目录
            $subpath = '/' . $this->getName($rule, $filename) . '/';

            if (!empty($subpath)) {
                $this->uploader->mkdir($this->config['save_path'] . $subpath);
            }
        }
        return $subpath;
    }

    /**
     * [getName 根据指定的规则获取文件或目录名称]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-29T16:03:15+0800
     * @param    [type]                   $rule     [规则]
     * @param    [type]                   $filename [原文件名]
     * @return   [type]                             [文件或目录名称]
     */
    private function getName($rule, $filename)
    {
        $name = '';
        if (is_array($rule)) { //数组规则
            $func     = $rule[0];
            $param    = (array)$rule[1];
            foreach ($param as &$value) {
               $value = str_replace('__FILE__', $filename, $value);
            }
            $name = call_user_func_array($func, $param);
        } elseif (is_string($rule)){ //字符串规则
            if (function_exists($rule)) {
                $name = call_user_func($rule);
            } else {
                $name = $rule;
            }
        }
        return $name;
    }
}
