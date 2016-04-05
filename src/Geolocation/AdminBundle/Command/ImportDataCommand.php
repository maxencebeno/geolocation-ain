<?php

namespace Geolocation\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Geolocation\AdminBundle\Entity\Cpf;

class ImportDataCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('importData')
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
        if (($handle = fopen(__DIR__ . '/../../../../web/data/cpf.csv', "r")) !== FALSE) {

//            $i = 0;
            $em = $this->getContainer()->get('doctrine')->getManager();



            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $data = array_map('utf8_encode', $data);
                $data = array_map('trim', $data);

                $sous_categorie = $em->getRepository("GeolocationAdminBundle:SousCategorie")->findOneBy(array('code' => trim($data[0])));
                $categorie = $em->getRepository("GeolocationAdminBundle:Categorie")->findOneBy(array('code' => trim($data[1])));
                $classe = $em->getRepository("GeolocationAdminBundle:Classe")->findOneBy(array('code' => trim($data[2])));
                $groupe = $em->getRepository("GeolocationAdminBundle:Groupe")->findOneBy(array('code' => trim($data[3])));
                $division = $em->getRepository("GeolocationAdminBundle:Division")->findOneBy(array('code' => trim($data[4])));
                $section = $em->getRepository("GeolocationAdminBundle:Section")->findOneBy(array('code' => trim($data[5])));

                if ($sous_categorie !== null && $categorie !== null && $classe !== null && $groupe !== null && $division !== null && $section !== null) {
                    $cpf = new Cpf();
                    $cpf->setSection($section);
                    $cpf->setGroupe($groupe);
                    $cpf->setClasse($classe);
                    $cpf->setDivision($division);
                    $cpf->setCategorie($categorie);
                    $cpf->setSouscategorie($sous_categorie);

                    $em->persist($cpf);
                } else {
                    echo "section : $data[5]\n";
                    echo "division : $data[4]\n";
                    echo "groupe : $data[3]\n";
                    echo "classe : $data[2]\n";
                    echo "categorie : $data[1]\n";
                    echo "sous categorie : $data[0]\n";
                }
            }

            $em->flush();
        }
        fclose($handle);

        $output->writeln('Command result.');
    }

}
