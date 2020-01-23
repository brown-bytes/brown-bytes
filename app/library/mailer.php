<?php

use Phalcon\Mvc\User\Component;
use Phalcon\Config\Adapter\Ini as ConfigIni;
use \Mailjet\Resources;
/**
 * Mailer
 *
 * Handles the mailer functions to make is easier to send emails
 */
class Mailer extends Component {
    private $response;
    private $success;
    /**
     * Sends an email on construct with the given parameters
     **/
    public function __construct($recipient, $subject, $body, $custom_config = False)
    {
        if ($custom_config) {
            $config = new ConfigIni('/brownbytesconfig/config.ini');
        } else {
            $config = $this->config;
        }
        $mj = new \Mailjet\Client($config->mailer->public, $config->mailer->private);
        $body = [
            'FromEmail' => "robot@brownbytes.org",
            'FromName' => "Brown Bytes",
            'Subject' => $subject,
            'Text-part' => $body,
            'Html-part' => $body,
            'Recipients' => [
                [
                    'Email' => $recipient
                ]
            ]
        ];
        $this->response = $mj->post(Resources::$Email, ['body' => $body]);
        $this->success = $this->response->success();
    }
    public function getResponse() {
        return $this->response;
    }
    public function getSuccess() {
        return $this->success;
    }
}
