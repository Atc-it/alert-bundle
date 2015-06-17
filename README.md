AtcAlertBundle
=============


### What now?

this bundle provides services to launch sms or email messages

## Config

    atc_alert:
        mail_from_default : "default@default.fr"
        sms_url : "https://rest.nexmo.com/sms/json"
        sms_key : "apiKey"
        sms_secret : "apiSecret"

## add to kernel

    ...
    $bundles = array(
        ...
        new Atc\Bundle\AlertBundle\AtcAlertBundle()
    );
    ...

### What in the future?

this bundle is meant to provide a preprogrammed alert system working whith cron 