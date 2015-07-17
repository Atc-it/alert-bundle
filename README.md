AtcAlertBundle
=============


## What now?

this bundle provides services to launch sms or email messages
For sms it uses an api like the one provided by nexmo

### Config

    atc_alert:
        mail_from_default : "default@default.fr"
        sms_from_default : "0102030405"
        sms_url : "https://rest.nexmo.com/sms/json"
        sms_key : "apiKey"
        sms_secret : "apiSecret"
        mandrill_secret : "mandrillApikeyOrNulToUseShiftmail"

### add to kernel

    ...
    $bundles = array(
        ...
        new Atc\Bundle\AlertBundle\AtcAlertBundle()
    );
    ...

## What in the future?

this bundle is meant to provide a preprogrammed alert system working whith cron 