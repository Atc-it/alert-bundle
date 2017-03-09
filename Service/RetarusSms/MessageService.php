<?php

namespace Atc\AlertBundle\Service\RetarusSms;

class MessageService
{
    private $recipients = array();
    private $text;

    /**
     * @param RecipientService $recipient
     *
     * @return $this
     */
    public function addRecipient(RecipientService $recipient)
    {
        $this->recipients[] = $recipient;

        return $this;
    }

    /**
     * @param string $messagetext
     *
     * @return $this
     */
    public function setMessage(string $messagetext)
    {
        $this->text = $messagetext;

        return $this;
    }
}
