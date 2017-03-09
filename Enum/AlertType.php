<?php

namespace Atc\AlertBundle\Enum;

/**
 * types of alert.
 *
 * @author Augustin
 */
abstract class AlertType
{
    const MAIL = 'MAIL';
    const SMS = 'SMS';
    const SMS_MAIL = 'SMS_MAIL';
}
