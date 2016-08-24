<?php

namespace Atc\Bundle\AlertBundle\Command;

use Atc\Bundle\AlertBundle\Manager\AlertManager;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('alert:update')
                ->setDescription('Send all already pass alertes which have been not sent yet')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $alertManager = $this->getContainer()->get('atc_alert.alert.manager');
        /* @var $alertManager AlertManager */

        //$alertManager->createMailAlert('test@toto.fr', 'TEST', 'test mail', null, new DateTime());
        $count = $alertManager->updateAlertes();

        $output->writeln($count.' alerts sent...');
    }

}
