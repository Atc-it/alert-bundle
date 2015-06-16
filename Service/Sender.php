<?php

namespace Atc\Bundle\AlertBundle\Service;

use Swift_Mailer;
use Swift_Message;

/**
 * Sender provides sms and mail sending functions
 *
 * @author martin
 */
class Sender {

    /**
     * default sending from
     * @var string 
     */
    protected $mail_from;

    /**
     * shiftmailer instance
     * @var Swift_Mailee 
     */
    protected $mailer;

    /**
     * nexmo api base url
     * @var string 
     */
    protected $sms_api_url;

    /**
     * nexmo api key
     * @var string 
     */
    protected $sms_api_key;

    /**
     * nexmo api secret
     * @var string 
     */
    protected $sms_api_secret;

    function __construct(Swift_Mailer $mailer, $mail_from, $sms_api_url, $sms_api_key, $sms_api_secret) {
        $this->mailer = $mailer;
        $this->mail_from = $mail_from;
        $this->sms_api_url = $sms_api_url;
        $this->sms_api_key = $sms_api_key;
        $this->sms_api_secret = $sms_api_secret;
    }

    /**
     * send a mail
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param string $from (otional, if not uses default mail_from)
     */
    public function sendMail($to, $subject, $body, $from = null) {
        if ($from === null) {
            $from = $this->mail_from;
        }
        $mail = Swift_Message::newInstance();

        $mail
                ->setFrom($from)
                ->setTo($to)
                ->setSubject($subject)
                ->setBody($body)
                ->setContentType('text/html');

        $this->mailer->send($mail);
    }

    /**
     * send a sms
     * @param string $to
     * @param string $body
     * @param string $from (otional, if not uses default mail_from)
     */
    public function sendSmS($to, $body, $from = null) {
        if ($from === null) {
            $from = $this->mail_from;
        }

        $curlUrl = $this->sms_api_url;
        $params = http_build_query([
            'api_key' => $this->sms_api_key,
            'api_secret' => $this->sms_api_secret,
            'from' => $from,
            'to' => $to,
            'text' => $body
        ]);
        $curlUrl = $curlUrl . '?' . $params;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $curlUrl,
        ));

        $resp = curl_exec($curl);
        curl_close($curl);
    }

}
