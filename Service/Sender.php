<?php

namespace Atc\Bundle\AlertBundle\Service;

use Atc\Bundle\AlertBundle\Entity\Alert;
use Atc\Bundle\AlertBundle\Enum\AlertType;
use DateTime;
use Doctrine\ORM\EntityManager;
use Mandrill;
use Swift_Mailer;
use Swift_Message;

/**
 * Sender provides sms and mail sending functions
 *
 * @author martin
 */
class Sender {

    /**
     * default sending mail from
     * @var string 
     */
    protected $mail_from;

    /**
     * default sending sms from
     * @var string 
     */
    protected $mail_from;

    /**
     * shiftmailer instance
     * @var Swift_Mailee 
     */
    protected $mailer;

    /**
     * the entity manager
     * @var EntityManager 
     */
    protected $em;

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

    /**
     * mandrill api secret
     * @var string 
     */
    protected $mandrill_api_secret;

    function __construct(
    Swift_Mailer $mailer, EntityManager $em, $mail_from, $sms_from, $sms_api_url, $sms_api_key, $sms_api_secret, $mandrill_api_secret
    ) {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->mail_from = $mail_from;
        $this->sms_from = $mail_from;
        $this->sms_api_url = $sms_api_url;
        $this->sms_api_key = $sms_api_key;
        $this->sms_api_secret = $sms_api_secret;
        $this->mandrill_api_secret = $mandrill_api_secret;
    }

    /**
     * sends a mail
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param string $from (otional, if not uses default mail_from)
     */
    protected function sendMail($to, $subject, $body, $from = null) {
        if ($from === null) {
            $from = $this->mail_from;
        }

        if (null === $this->mandrill_api_secret) {

            $mail = Swift_Message::newInstance();

            $mail
                    ->setFrom($from)
                    ->setTo($to)
                    ->setSubject($subject)
                    ->setBody($body)
                    ->setContentType('text/html');

            $this->mailer->send($mail);
        } else {

            $mandrill = new Mandrill($this->mandrill_api_secret);
            $message = array(
                'html' => $body,
                'subject' => $subject,
                'from_email' => $from,
                'to' => array(
                    array(
                        'email' => $to,
                        'type' => 'to'
                    )
                )
            );
            $mandrill->messages->send($message, false, 'Main Pool');
        }
    }

    /**
     * sends a sms
     * @param string $to
     * @param string $body
     * @param string $from (otional, if not uses default mail_from)
     */
    protected function sendSmS($to, $body, $from = null) {
        if ($from === null) {
            $from = $this->sms_from;
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

    /**
     * sends a sms from an alert
     * @param Alert $alert
     */
    public function sendSmsAlert(Alert $alert) {
        $this->sendSmS(
                $alert->getToSms(), $alert->getBody(), $alert->getFromF()
        );
    }

    /**
     * sends a mail from an alert
     * @param Alert $alert
     */
    public function sendMailAlert(Alert $alert) {
        $this->sendMail(
                $alert->getToMail(), $alert->getSubject(), $alert->getBody(), $alert->getFromF()
        );
    }

}
