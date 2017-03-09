<?php

namespace Atc\AlertBundle\Service\RetarusSms;

class OptionsService
{
    private $source;
    private $encoding;
    private $billCode;
    private $statusRequested;
    private $flash;
    private $customerRef;
    private $validityMin;
    private $maxParts;
    private $qos;

    /**
     * OptionsService constructor.
     *
     * @param null $source
     * @param null $encoding
     * @param null $billCode
     * @param null $statusRequested
     * @param null $flash
     * @param null $validityMin
     * @param null $maxParts
     * @param null $qos
     */
    public function __construct(
        $source = null,
        $encoding = null,
        $billCode = null,
        $statusRequested = null,
        $flash = null,
        $validityMin = null,
        $maxParts = null,
        $qos = null
    ) {
        $this->source = $source;
        $this->encoding = $encoding;
        $this->billCode = $billCode;
        $this->statusRequested = $statusRequested;
        $this->flash = $flash;
        $this->validityMin = $validityMin;
        $this->maxParts = $maxParts;
        $this->qos = $qos;
    }

    /**
     * @param string $customerRef
     *
     * @return $this
     */
    public function setCustomerRef(string $customerRef)
    {
        $this->customerRef = $customerRef;

        return $this;
    }
}
