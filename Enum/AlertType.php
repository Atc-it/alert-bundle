<?php

namespace Atc\Bundle\AlertBundle\Enum;


/**
 * types of alert
 *
 * @author martin
 */
abstract class AlertType {
    const MAIL = 'MAIL';
    const SMS = 'SMS';
    const SMS_MAIL = 'SMS_MAIL';
}
