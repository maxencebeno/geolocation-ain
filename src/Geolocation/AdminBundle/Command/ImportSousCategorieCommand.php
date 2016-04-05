<?php

namespace Geolocation\AdminBundle\Command;

use Geolocation\AdminBundle\Entity\Section;
use Geolocation\AdminBundle\Entity\SousCategorie;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Geolocation\AdminBundle\Entity\Cpf;

class ImportSousCategorieCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('importSousCategorie')
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
        if (($handle = fopen(__DIR__ . '/../../../../web/data/sous_categorie.csv', "r")) !== FALSE) {

            $i = 0;
            $em = $this->getContainer()->get('doctrine')->getManager();
            
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $data = array_map('utf8_encode', $data);
                $data = array_map('trim', $data);

                $code = $data[0];
                $libelle = $data[1];

                if ($code !== "" && $libelle !== "") {
                    $section = new SousCategorie();
                    
                    $section->setAffiche(true);
                    $section->setCode($code);
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
