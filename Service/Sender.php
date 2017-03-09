<?php

namespace Atc\AlertBundle\Service;

use Atc\AlertBundle\Entity\Alert;
use Doctrine\ORM\EntityManager;
use Swift_Mailer;
use Swift_Message;
use Mailjet\Client as MailjetClient;
use Mailjet\Resources;
use Atc\AlertBundle\Service\RetarusSms\SmsService;
use Symfony\Bridge\Monolog\Logger;

/**
 * Sender provides sms and mail sending functions.
 *
 * @author augustin
 */
class Sender
{
    /**
     * default sending mail from.
     *
     * @var string
     */
    protected $mailFrom;

    /**
     * default sending sms from.
     *
     * @var string
     */
    protected $smsFrom;

    /**
     * shiftmailer instance.
     *
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * the entity manager.
     *
     * @var EntityManager
     */
    protected $em;

    /**
     * nexmo api base url.
     *
     * @var string
     */
    protected $smsApiUrl;

    /**
     * nexmo api key.
     *
     * @var string
     */
    protected $smsApiKey;

    /**
     * nexmo api secret.
     *
     * @var string
     */
    protected $smsApiSecret;

    /**
     * international préfix.
     *
     * @var string
     */
    protected $smsItnPrefix;

    /**
     * Mailjet api public key.
     *
     * @var string
     */
    protected $mailjetApiPublicKey;

    protected $senderName;

    /**
     * Mailjet api private key.
     *
     * @var string
     */
    protected $mailjetApiPrivateKey;

    /** @var SmsService $retarusSmsService */
    protected $retarusSmsService;

    /**
     * Sender constructor.
     *
     * @param Swift_Mailer  $mailer
     * @param EntityManager $em
     * @param SmsService    $retarusSmsService
     * @param               $mail_from
     * @param               $sms_from
     * @param               $sms_api_url
     * @param               $sms_api_key
     * @param               $sms_api_secret
     * @param               $sms_itn_prefix
     * @param               $mailjet_api_public_key
     * @param               $mailjet_api_private_key
     */
    public function __construct(
        Swift_Mailer $mailer,
        EntityManager $em,
        SmsService $retarusSmsService,
        $mail_from,
        $sms_from,
        $sms_api_url,
        $sms_api_key,
        $sms_api_secret,
        $sms_itn_prefix,
        $mailjet_api_public_key,
        $mailjet_api_private_key,
        $senderName
    ) {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->retarusSmsService = $retarusSmsService;
        $this->mailFrom = $mail_from;
        $this->smsFrom = $sms_from;
        $this->smsApiUrl = $sms_api_url;
        $this->smsApiKey = $sms_api_key;
        $this->smsApiSecret = $sms_api_secret;
        $this->smsItnPrefix = $sms_itn_prefix;
        $this->mailjetApiPublicKey = $mailjet_api_public_key;
        $this->mailjetApiPrivateKey = $mailjet_api_private_key;
        $this->senderName = $senderName;
    }

    /**
     * Sends a mail
     *
     * @param $to
     * @param $subject
     * @param $body
     * @param null $from (otional, if not uses default mail_from)
     *
     * @throws \Exception
     */
    public function sendMail($to, $subject, $body, $from = null)
    {
        if ($from === null) {
            $from = $this->mailFrom;
        }

        if (null === $this->mailjetApiPrivateKey || null === $this->mailjetApiPublicKey) {
            $mail = Swift_Message::newInstance();

            $mail
                    ->setFrom($from)
                    ->setTo($to)
                    ->setSubject($subject)
                    ->setBody($body)
                    ->setContentType('text/html');

            $this->mailer->send($mail);
        } else {
            $mailjetClient = new MailjetClient($this->mailjetApiPublicKey, $this->mailjetApiPrivateKey);
            $message = [
                'FromEmail' => $from,
                'FromName' => $this->senderName,
                'Subject' => $subject,
                'Text-part' => $body,
                'Html-part' => $body,
                'Recipients' => [['Email' => $to]],
            ];

            try {
                $response = $mailjetClient->post(Resources::$Email, ['body' => $message]);

                if ($response->getStatus() != 200) {
                    throw new \Exception('Something went wrong with mailjet.');
                }
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

    /**
     * Send a sms.
     *
     * @param string $to    Exemple: 0033610637990
     * @param string $body
     * @param null   $from (otional, if not uses default mail_from)
     *
     * @return mixed
     */
    protected function sendSmS($to, $body, $from = null) {
        if ($from === null) {
            $from = $this->sms_from == null ? 'AlertBundle' : $this->sms_from;
        }

        if ($this->sms_itn_prefix !== null && $to[0] === '0') {
            $to = substr_replace($to, $this->sms_itn_prefix, 0, 1);
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

        return $resp;
    }
    
    protected function sendRetarusSmS(string $to, string $body, $from = null)
    {
        return $this->retarusSmsService->sendSms($to, 'ref1', $body);
    }
    /**
     * sends a sms from an alert.
     *
     * @param Alert $alert
     *
     * @return mixed
     */
    public function sendSmsAlert(Alert $alert)
    {
        return $this->sendSmS($alert->getToSms(), $alert->getBody(), $alert->getFromF());
    }

    /**
     * sends a mail from an alert.
     *
     * @param Alert $alert
     */
    public function sendMailAlert(Alert $alert)
    {
        $this->sendMail(
                $alert->getToMail(), $alert->getSubject(), $alert->getBody(), $alert->getFromF()
        );
    }
}
