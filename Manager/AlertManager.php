<?php

namespace Atc\AlertBundle\Manager;

use Atc\AlertBundle\Entity\Alert;
use Atc\AlertBundle\Enum\AlertType;
use Atc\AlertBundle\Service\Sender;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;

/**
 * Mange Alert Entity.
 *
 * @author Augustin
 */
class AlertManager
{
    /** @var EntityManager  */
    protected $em;
    /** @var Sender  */
    protected $sender;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * AlertManager constructor.
     *
     * @param EntityManager $em
     * @param Sender $sender
     * @param Logger $logger
     */
    public function __construct(EntityManager $em, Sender $sender, Logger $logger)
    {
        $this->em = $em;
        $this->sender = $sender;
        $this->logger = $logger;
    }

    /**
     * Fetch all non sent pending alerts and send them.
     *
     * @return int Number of sended alerts
     */
    public function updateAlerts()
    {
        $alertRepo = $this->em->getRepository('AtcAlertBundle:Alert');
        $alerts = $alertRepo->findPending();

        foreach ($alerts as $alert) {
            $this->sendAlert($alert);
            $alert->setSent(true);
            $alert->setDate(new \DateTime());

            $this->em->persist($alert);
        }

        $this->em->flush();

        return count($alerts);
    }

    /**
     * sends an Alert.
     *
     * @param Alert $alert
     */
    public function sendAlert(Alert $alert)
    {
        try {
            switch ($alert->getType()) {
                case AlertType::MAIL:
                    $this->sender->sendMailAlert($alert);
                    break;
                case AlertType::SMS:
                    $this->sender->sendSmsAlert($alert);
                    break;
                case AlertType::SMS_MAIL:
                    $this->sender->sendSmsAlert($alert);
                    $this->sender->sendMailAlert($alert);
                    break;
            }
        } catch (\Exception $e) {
            if ($alert->getId() != 0) {
                $this->logger->error(sprintf('Alert with ID %d not sent. Type : ', $alert->getId(), $alert->getType()));
            }
        }
    }

    /**
     * Create a sms alert.
     *
     * @param string $to
     * @param string $body
     * @param null   $from If null passed use default from configuration
     * @param null   $date If null passed the alert is send instantely
     */
    public function createSmsAlert(string $to, string $body, $from = null, $date = null)
    {
        $alert = $this->hydrateAlert(AlertType::SMS, $body, $to, null, null, $from, $date);

        if ($date == null) {
            $this->sendAlert($alert);
            $alert->setSent(true);
            $alert->setDate(new \DateTime());
        }
        $this->em->persist($alert);
        $this->em->flush();
    }

    /**
     * Create a mail alert.
     *
     * @param string $to
     * @param string $body
     * @param string $subject
     * @param null $from If null passed use default from configuration
     * @param null $date If null passed the alert is send instantely
     * @return Alert
     */
    public function createMailAlert(string $to, string $body, string $subject, $from = null, $date = null)
    {
        $alert = $this->hydrateAlert(AlertType::MAIL, $body, null, $to, $subject, $from, $date);

        if ($date == null) {
            $this->sendAlert($alert);
            $alert->setSent(true);
            $alert->setDate(new \DateTime());
        }
        $this->em->persist($alert);
        $this->em->flush();
    }

    /**
     * Send imédiate mail without historize it.
     *
     * @param string $to
     * @param string $body
     * @param string $subject
     * @param null   $from    If null passed use default from configuration
     */
    public function sendMail(string $to, string $body, string $subject, $from = null)
    {
        $this->sender->sendMail($to, $subject, $body, $from);
    }

    /**
     * Create a sms/mail alert.
     *
     * @param      $toSms
     * @param      $toMail
     * @param      $body
     * @param      $subject
     * @param null $from    If null passed use default from configuration
     * @param null $date    If null passed the alert is send instantely
     */
    public function createSmsMailAlert($toSms, $toMail, $body, $subject, $from = null, $date = null)
    {
        $alert = $this->hydrateAlert(AlertType::SMS_MAIL, $body, $toSms, $toMail, $subject, $from, $date);

        if ($date == null) {
            $this->sendAlert($alert);
            $alert->setSent(true);
            $alert->setDate(new \DateTime());
        }
        $this->em->persist($alert);
        $this->em->flush();
    }

    private function hydrateAlert($type, $body, $toSms = null, $toMail = null, $subject = null, $from = null, $date = null)
    {
        $alert = new Alert();
        $alert->setType($type);
        $alert->setBody($body);
        $alert->setFromF($from);
        $alert->setSent(false);
        $alert->setSubject($subject);
        $alert->setToSms($toSms);
        $alert->setToMail($toMail);
        $alert->setDate($date);

        return $alert;
    }
}
