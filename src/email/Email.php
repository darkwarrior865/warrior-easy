<?php
namespace darkwarrior\easy\email;

use darkwarrior\easy\email\EmailException;

/**
 * 
 */
class Email
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
     * @DateTime 2022-07-11T19:17:12+0800
     * @return   [type]                   [description]
     */
    public function send()
    {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {
            // 调试模式输出
            $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_OFF;
            // 使用SMTP
            $mail->isSMTP();

            $mail->Host       = $this->config->getHost();
            // 允许 SMTP 认证
            $mail->SMTPAuth   = true;

            $mail->Username   = $this->config->getUser();
            $mail->Password   = $this->config->getPass();
            // 允许TLS或者ssl协议
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $this->config->getPort();

            $mail->addAddress($this->sendBody->getReceiver());  //$mail->addAddress('ellen@example.com', 'Joe User');
            $mail->setFrom($this->config->getFromEmail(), $this->config->getFromName());
            $mail->addReplyTo($this->config->getReplyEmail(), $this->config->getReplyName());
            // $mail->addCC('cc@example.com');   //抄送
            // $mail->addBCC('bcc@example.com'); //密送

            // 附件
            //$mail->addAttachment('/var/tmp/file.tar.gz');       // 添加附件
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');  // 发送附件并且重命名

            $mail->isHTML(true);
            $mail->Subject = $this->sendBody->getSubject();
            $mail->Body    = $this->sendBody->getBody();
            //$mail->AltBody = '这是非HTML邮件客户端的纯文本正文';

            $result = $mail->send();

        } catch (\PHPMailer\PHPMailer\Exception $e) {
            throw new EmailException($e->getMessage());
        }

        if ($result == false) {
            throw new EmailException('send fail!');
        }

        return true;
    }
}
