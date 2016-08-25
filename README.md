AtcAlertBundle
=============


## What now?

this bundle provides services to launch sms or email messages
For sms it uses an api like the one provided by nexmo


## What in the future?

this bundle is meant to provide a preprogrammed alert system working whith cron


Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require atc/alert-bundle "~1"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Atc\Bundle\AlertBundle\AtcAlertBundle(),
        );

        // ...
    }

    // ...
}
```


Config
======

    atc_alert:
        mail_from_default : "default@default.fr"
        sms_from_default : "0102030405"
        sms_url : "https://rest.nexmo.com/sms/json"
        sms_key : "apiKey"
        sms_secret : "apiSecret"
        mailjet_public : "mailjetApikeyOrNulToUseShiftmail"
        mailjet_private : "mailjetApikeyOrNulToUseShiftmail"
