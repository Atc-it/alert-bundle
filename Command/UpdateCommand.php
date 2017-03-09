<?php

namespace Atc\AlertBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
                ->setName('alert:update')
                ->setDescription('Send all already pass alerts which have been not sent yet')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $alertManager = $this->getContainer()->get('atc_alert.alert.manager');

        $count = $alertManager->updateAlerts();

        $output->writeln($count.' alerts sent...');
    }
}
