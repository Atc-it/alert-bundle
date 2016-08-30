<?php

namespace Atc\Bundle\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alert
 *
 * @ORM\Table(name="`atc_alert`")
 * @ORM\Entity(repositoryClass="Atc\Bundle\AlertBundle\Entity\AlertRepository")
 */
class Alert
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="toSms", type="string", length=16, nullable=true)
     */
    protected $toSms;

    /**
     * @var string
     *
     * @ORM\Column(name="toMail", type="string", length=255, nullable=true)
     */
    protected $toMail;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=512, nullable=true)
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="string", length=4096)
     */
    protected $body;

    /**
     * @var string
     *
     * @ORM\Column(name="fromF", type="string", length=255, nullable=true)
     */
    protected $fromF;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=32)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;

    /**
     * @var string
     *
     * @ORM\Column(name="sent", type="boolean")
     */
    protected $sent;

    public function getId()
    {
        return $this->id;
    }

    public function setToSms($toSms)
    {
        $this->toSms = $toSms;
        return $this;
    }

    public function getToSms()
    {
        return $this->toSms;
    }

    public function setToMail($toMail)
    {
        $this->toMail = $toMail;
        return $this;
    }

    public function getToMail()
    {
        return $this->toMail;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setFromF($fromF)
    {
        $this->fromF = $fromF;
        return $this;
    }

    public function getFromF()
    {
        return $this->fromF;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    function getDate() {
        return $this->date;
    }

    function getSent() {
        return $this->sent;
    }

    function setDate($date) {
        $this->date = $date;
        return $this;
    }

    function setSent($sent) {
        $this->sent = $sent;
        return $this;
    }


}
