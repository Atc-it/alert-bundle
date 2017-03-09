<?php

namespace Atc\AlertBundle\Service\RetarusSms;

class SmsService
{
    /** @var MessageService */
    private $messageService;
    /** @var OptionsService */
    private $optionsService;
    /** @var RecipientService */
    private $recipientService;
    /** @var SendJobRequestService */
    private $sendJobRequestService;

    private $retarusSoapUrl;

    /**
     * SmsService constructor.
     *
     * @param MessageService        $messageService
     * @param OptionsService        $optionsService
     * @param RecipientService      $recipientService
     * @param SendJobRequestService $sendJobRequestService
     * @param string                $retarusSoapUrl
     */
    public function __construct(
        MessageService $messageService,
        OptionsService $optionsService,
        RecipientService $recipientService,
        SendJobRequestService $sendJobRequestService,
        string $retarusSoapUrl
    ) {
        $this->messageService = $messageService;
        $this->optionsService = $optionsService;
        $this->recipientService = $recipientService;
        $this->sendJobRequestService = $sendJobRequestService;
        $this->retarusSoapUrl = $retarusSoapUrl;
    }

    /**
     * @param string $num
     * @param string $ref
     * @param string $msg
     *
     * @return array
     */
    public function sendSms(string $num, string $ref, string $msg):array
    {
        /** @var SendJobRequestService $jobRequestService */
        $jobRequestService = $this->configRequest($num, $ref, $msg);

        return $this->soapCall($jobRequestService);
    }

    /**
     * @param string $num
     * @param string $ref
     * @param string $msg
     *
     * @return SendJobRequestService
     */
    public function configRequest(string $num, string $ref, string $msg):SendJobRequestService
    {
        $recipientService = $this->configRecipient($num, $ref);
        $messageService = $this->configMessage(array($recipientService), $msg);
        $optionsService = $this->configOptions();
        $jobRequestService = $this->configJobRequest($optionsService, array($messageService));

        return $jobRequestService;
    }

    /**
     * @param SendJobRequestService $jobRequestService
     * @return array
     *
     * @throws \Exception
     */
    public function soapCall(SendJobRequestService $jobRequestService):array
    {
        try {
            $client = new \SoapClient($this->retarusSoapUrl, array('trace' => 1));
            $response = $client->__soapCall('sendJob', array($jobRequestService));

            return array('jobId' => $response->jobId);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param string $num
     * @param string $ref
     *
     * @return RecipientService
     */
    public function configRecipient(string $num, string $ref): RecipientService
    {
        $this->recipientService->setDestination($num);
        $this->recipientService->setCustomerRef($ref);

        return $this->recipientService;
    }

    /**
     * @param array  $recipients
     * @param string $msg
     *
     * @return MessageService
     */
    public function configMessage(array $recipients, string $msg):MessageService
    {
        /** @var RecipientService $recipientService */
        foreach ($recipients as $recipientService) {
            $this->messageService->addRecipient($recipientService);
        }

        $this->messageService->setMessage($msg);

        return $this->messageService;
    }

    /**
     * @return OptionsService
     */
    public function configOptions():OptionsService
    {
        $this->optionsService->setCustomerRef('one and one');

        return $this->optionsService;
    }

    /**
     * @param OptionsService $optionsService
     * @param array          $messages
     *
     * @return SendJobRequestService
     */
    public function configJobRequest(OptionsService $optionsService, array $messages):SendJobRequestService
    {
        $this->sendJobRequestService->setOptions($optionsService);

        /** @var MessageService $messageService */
        foreach ($messages as $messageService) {
            $this->sendJobRequestService->addMessage($messageService);
        }

        return $this->sendJobRequestService;
    }
}
