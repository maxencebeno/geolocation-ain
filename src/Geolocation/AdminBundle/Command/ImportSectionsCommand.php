<?php

namespace Geolocation\AdminBundle\Command;

use Geolocation\AdminBundle\Entity\Section;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Geolocation\AdminBundle\Entity\Cpf;

class ImportSectionsCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('importSections')
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
        if (($handle = fopen(__DIR__ . '/../../../../web/data/section.csv', "r")) !== FALSE) {

            $i = 0;
            $em = $this->getContainer()->get('doctrine')->getManager();
            
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $data = array_map('utf8_encode', $data);
                $data = array_map('trim', $data);

                $code = $data[0];
                $libelle = $data[1];

                if ($code !== "" && $libelle !== "") {
                    $section = new Section();
                    
                    $section->setAffiche(true);
                    $section->setCode($code);
                    $section->setImage(null);
                    $section->setLibelle($libelle);

                    $em->persist($section);
                } else {
                    echo "erreur ligne $i\n";
                }
                $i++;
            }

            $em->flush();
        }
        fclose($handle);

        $output->writeln('Command result.');
    }

}
