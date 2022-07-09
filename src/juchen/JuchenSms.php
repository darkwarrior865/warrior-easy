<?php
namespace darkwarrior\easy\juchen;

use darkwarrior\easy\juchen\JuchenException;

/**
 * class JuchenSms
 * 巨辰短信服务
 * @author 黑暗中的武者
 */
class JuchenSms
{
    /**
     * [$config description]
     * @var [type]
     */
    private $config;

    /**
     * [$sendBody description]
     * @var [type]
     */
    private $sendBody;

    /**
     * [__construct description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-08T13:37:07+0800
     */
    public function __construct($config, $sendBody)
    {
        $this->config   = $config;
        $this->sendBody = $sendBody;
    }

    /**
     * [send description]
     * @Author   黑暗中的武者
     * @DateTime 2022-07-08T13:37:11+0800
     * @return   [type]                   [description]
     */
    public function send()
    {
        // 创建请求客户端
        $client = new \GuzzleHttp\Client();
        // 请求接口
        $response = $client->request('POST', $this->config->getApiUrl(), [
            'form_params' => [
                'username'   => $this->config->getUsername(),
                'passwd'     => $this->config->getPassword(),
                'phone'      => $this->sendBody->getReceiver(),
                'msg'        => $this->sendBody->getContent(),
                'needstatus' => 'true',
                'port'       => '',
                'sendtime'   => ''
            ]
        ]);

        $result = json_decode($response->getBody()->getContents());

        if ($result->respcode != 0) {
            throw new JuchenException($result->respdesc);
        }
        return true;
    }
}