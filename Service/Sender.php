<?php

namespace Atc\Bundle\AlertBundle\Service;

use Atc\Bundle\AlertBundle\Entity\Alert;
use Atc\Bundle\AlertBundle\Enum\AlertType;
use DateTime;
use Doctrine\ORM\EntityManager;
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

    function __construct(
    Swift_Mailer $mailer, EntityManager $em, $mail_from, $sms_api_url, $sms_api_key, $sms_api_secret
    ) {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->mail_from = $mail_from;
        $this->sms_api_url = $sms_api_url;
        $this->sms_api_key = $sms_api_key;
        $this->sms_api_secret = $sms_api_secret;
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
     * sends a sms
     * @param string $to
     * @param string $body
     * @param string $from (otional, if not uses default mail_from)
     */
    protected function sendSmS($to, $body, $from = null) {
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

    /**
     * sends a sms from an alert
     * @param Alert $alert
     */
    protected function sendSmsAlert(Alert $alert) {
        $this->sendSmS(
                $alert->getToSms(), $alert->getBody(), $alert->getFromF()
        );
    }

    /**
     * sends a mail from an alert
     * @param Alert $alert
     */
    protected function sendMailAlert(Alert $alert) {
        $this->sendMail(
                $alert->getToMail(), $alert->getSubject(), $alert->getBody(), $alert->getFromF()
        );
    }

    /**
     * sends an Alert
     * @param Alert $alert
     */
    public function sendAlert(Alert $alert) {
        switch ($alert->getType()) {
            case AlertType::MAIL :
                $this->sendMailAlert($alert);
                break;
            case AlertType::SMS :
                $this->sendSmsAlert($alert);
                break;
            case AlertType::SMS_MAIL :
                $this->sendSmsAlert($alert);
                $this->sendMailAlert($alert);
                break;
        }

        if ($alert->getType() === AlertType::MAIL) {
            $this->sendMailAlert($alert);
        }

        $alert->setSent(true);
        $alert->setDate(new DateTime());

        $this->em->persist($alert);
        $this->em->flush();
    }

    /**
     * creates a sms alert
     * @param type $to
     * @param type $body
     * @param type $from if null passed use default from configuration
     * @param type $date if null passed the alert is send instantely
     */
    public function createSmsAlert($to, $body, $from = null, $date = null) {
        $alert = new Alert();
        $alert->setType(AlertType::SMS);
        $alert->setBody($body);
        $alert->setFromF($from);
        $alert->setSent(false);
        $alert->setToSms($to);
        $alert->setDate($date);

        if ($date == null) {
            $this->sendAlert($alert);
        } else {
            $this->em->persist($alert);
            $this->em->flush();
        }
    }

    /**
     * create a mail alert
     * @param type $to
     * @param type $body
     * @param type $subject
     * @param type $from if null passed use default from configuration
     * @param type $date if null passed the alert is send instantely
     */
    public function createMailAlert($to, $body, $subject, $from = null, $date = null) {
        $alert = new Alert();
        $alert->setType(AlertType::MAIL);
        $alert->setBody($body);
        $alert->setFromF($from);
        $alert->setSent(false);
        $alert->setSubject($subject);
        $alert->setToMail($to);
        $alert->setDate($date);

        if ($date == null) {
            $this->sendAlert($alert);
        } else {
            $this->em->persist($alert);
            $this->em->flush();
        }
    }

    /**
     * create a sms/mail alert
     * @param type $toSms
     * @param type $toMail
     * @param type $body
     * @param type $subject
     * @param type $from if null passed use default from configuration
     * @param type $date if null passed the alert is send instantely
     */
    public function createSmsMailAlert($toSms, $toMail, $body, $subject, $from = null, $date = null) {
        $alert = new Alert();
        $alert->setType(AlertType::SMS_MAIL);
        $alert->setBody($body);
        $alert->setFromF($from);
        $alert->setSent(false);
        $alert->setSubject($subject);
        $alert->setToSms($toSms);
        $alert->setToMail($toMail);
        $alert->setDate($date);

        if ($date == null) {
            $this->sendAlert($alert);
        } else {
            $this->em->persist($alert);
            $this->em->flush();
        }
    }

}
