<?php

/**
 * Created by PhpStorm.
 * User: augustindelaveaucoupet
 * Date: 19/10/2016
 * Time: 16:10
 */
use Atc\AlertBundle\Service\Sender;
use FM\ToolsBundle\TestTools\FMWebTestCase;
use Doctrine\ORM\EntityManager;
use Atc\AlertBundle\Service\RetarusSms\SmsService;

class SenderTest extends FMWebTestCase
{
    private $sender;

    public function setUp()
    {
        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();


        $sender = $this->getMockBuilder(Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $smsService = $this->getMockBuilder(SmsService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sender = new Sender(
            $sender,
            $em,
            $smsService,
            'mail_from',
            'from',
            'sms_api_url',
            'sms_api_key',
            'sms_api_secret',
            'sms_itn_prefix',
            'mailjet_api_public_key',
            'mailjet_api_private_key',
            'Prince ali'
        );
    }
    public function testSendMailSender()
    {
        try {
            $this->invokeMethod(
                $this->sender,
                'sendMail',
                [
                    'toto@toto.fr',
                    'TEST',
                    'test mail body'
                ]
            );

            $this->fail('Mails shouldn\'t be sent in tests');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    public function testSendSmsSender()
    {
        $this->invokeMethod(
            $this->sender,
            'sendSmS',
            [
                '06123456789',
                'test sms body'
            ]
        );
    }
}
