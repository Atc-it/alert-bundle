<?php

namespace Atc\Bundle\AlertBundle\Manager;

use Atc\Bundle\AlertBundle\Entity\Alert;
use Atc\Bundle\AlertBundle\Enum\AlertType;
use Atc\Bundle\AlertBundle\Service\Sender;
use DateTime;
use Doctrine\ORM\EntityManager;

/**
 * Mange Alert Entity
 *
 * @author Augustin
 */
class AlertManager {

    /**
     * the entity manager
     * @var EntityManager
     */
    protected $em;

    /**
     * the Sender service
     * @var Sender
     */
    protected $sender;

    function __construct(EntityManager $em, Sender $sender) {
        $this->em = $em;
        $this->sender = $sender;
    }

    /**
     * fetch all non sent pending alerts and send them
     * @return the number of sended alerts
     */
    public function updateAlertes() {
        $alertRepo = $this->em->getRepository('AtcAlertBundle:Alert');
        $alerts = $alertRepo->findPending();

        foreach ($alerts as $alert) {
            $this->sendAlert($alert);
        }

        $this->em->flush();
        return count($alerts);
    }

    /**
     * sends an Alert
     * @param Alert $alert
     */
    public function sendAlert(Alert $alert) {
        switch ($alert->getType()) {
            case AlertType::MAIL :
                $this->sender->sendMailAlert($alert);
                break;
            case AlertType::SMS :
                $this->sender->sendSmsAlert($alert);
                break;
            case AlertType::SMS_MAIL :
                $this->sender->sendSmsAlert($alert);
                $this->sender->sendMailAlert($alert);
                break;
        }

        $alert->setSent(true);
        $alert->setDate(new DateTime());

        $this->em->persist($alert);
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
        }
        $this->em->persist($alert);
        $this->em->flush();
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
        }
        $this->em->persist($alert);
        $this->em->flush();
    }
    
    /**
     * send imédiate mail without historize it
     * @param type $to
     * @param type $body
     * @param type $subject
     * @param type $from if null passed use default from configuration
     */
    public function sendMail($to, $body, $subject, $from = null) {
        $this->sender->sendMail($to, $subject, $body, $from = null);
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
