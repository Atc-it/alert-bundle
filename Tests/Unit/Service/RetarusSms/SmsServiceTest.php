<?php
/**
 * Created by PhpStorm.
 * User: augustindelaveaucoupet
 * Date: 19/10/2016
 * Time: 17:29
 */

namespace Atc\AlertBundle\Service\RetarusSms;

use FM\ToolsBundle\TestTools\FMWebTestCase;
use Atc\AlertBundle\Service\RetarusSms\SmsService;
use Atc\AlertBundle\Service\RetarusSms\MessageService;
use Atc\AlertBundle\Service\RetarusSms\OptionsService;
use Atc\AlertBundle\Service\RetarusSms\RecipientService;
use Atc\AlertBundle\Service\RetarusSms\SendJobRequestService;

class SmsServiceTest extends FMWebTestCase
{
    private $smsService;

    public function setUp()
    {
        $messageService = $this->getMockBuilder(MessageService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $optionsService = $this->getMockBuilder(OptionsService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $recipientService = $this->getMockBuilder(RecipientService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $sendJobRequestService = $this->getMockBuilder(SendJobRequestService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->smsService = new SmsService(
            $messageService,
            $optionsService,
            $recipientService,
            $sendJobRequestService,
            'http://'
        );
    }

    public function testSendSmsService()
    {
        $client = static::createClient();
        $client->enableProfiler();

//        $this->invokeMethod(
//            $this->smsService,
//            'sendSms',
//            [
//                '0631030186',
//                'TEST',
//                'test mail'
//            ]
//        );
    }
}
