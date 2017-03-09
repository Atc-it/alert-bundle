<?php

namespace Atc\AlertBundle\Tests\Unit\Manager;

use Atc\AlertBundle\Manager\AlertManager;
use FM\ToolsBundle\TestTools\FMWebTestCase;
use Doctrine\ORM\EntityManager;
use Atc\AlertBundle\Service\Sender;
use Monolog\Logger;

class AlertManagerTest extends FMWebTestCase
{
    private $alertManager;

    public function setUp()
    {
        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $entityManager
            ->method('persist')
            ->will($this->returnValue(null));

        $sender = $this->getMockBuilder(Sender::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->createServiceMock('logger', Logger::class);

        $this->alertManager = new AlertManager(
            $entityManager,
            $sender,
            $this->logger
        );
    }

    public function testCreateMailAlert()
    {
        $client = static::createClient();
        $client->enableProfiler();

        $this->invokeMethod(
            $this->alertManager,
            'createMailAlert',
            [
                'toto@toto.fr',
                'TEST',
                'test mail'
            ]
        );
        $this->invokeMethod(
            $this->alertManager,
            'createSmsMailAlert',
            [
                '060123456789',
                'toto@toto.fr',
                'test mail',
                'TEST',
                null,
                null
            ]
        );
        $this->invokeMethod(
            $this->alertManager,
            'createSmsAlert',
            [
                '060123456789',
                'test sms body',
                null,
                null
            ]
        );
        $this->invokeMethod(
            $this->alertManager,
            'sendMail',
            [
                'toto@titi.fr',
                'mail body',
                'subject',
                null
            ]
        );
    }
}
