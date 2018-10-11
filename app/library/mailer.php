<?php

use Phalcon\Mvc\User\Component;
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
    public function __construct($recipient, $subject, $body)
    {
        
        $mj = new \Mailjet\Client('3ea98d36a9b0c2519e7d3699a93f2c82', '44d2586dee20675d84bfd4e4c67583b5');
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
        $this->success = $response->success();
    }
    public function getResponse() {
        return $this->response;
    }
    public function getSuccess() {
        return $this->success;
    }
}
