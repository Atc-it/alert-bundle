<?php

namespace Atc\AlertBundle\Service\RetarusSms;

class RecipientService
{
    private $dst;
    private $customerRef;

    /**
     * @param string $destination
     *
     * @return $this
     */
    public function setDestination(string $destination)
    {
        $this->dst = $destination;

        return $this;
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
