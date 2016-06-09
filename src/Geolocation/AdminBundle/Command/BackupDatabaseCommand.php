<?php

namespace Geolocation\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Geolocation\AdminBundle\Entity\Cpf;

class BackupDatabaseCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('backupDatabase')
                ->setDescription('...')
                ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
                ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $argument = $input->getArgument('argument');

        if ($input->getOption('option')) {
            // ...
        }


        set_time_limit(0);
        ini_set('memory_limit', '5120M');

        system("mysql -u geoloc -p\"49wPNR495y\" -P 3306 -h 127.0.0.1 --all-databases > backup" . date('Ymd') . ".sql");
    }

}
