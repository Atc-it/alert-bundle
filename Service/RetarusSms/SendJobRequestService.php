<?php

namespace Atc\AlertBundle\Service\RetarusSms;

class SendJobRequestService
{
    private $username;
    private $password;
    private $options;
    private $messages = array();

    /**
     * SendJobRequestService constructor.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param MessageService $message
     *
     * @return $this
     */
    public function addMessage(MessageService $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * @param OptionsService $options
     *
     * @return $this
     */
    public function setOptions(OptionsService $options)
    {
        $this->options = $options;

        return $this;
    }
}
