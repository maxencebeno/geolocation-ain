<?php
/**
 * Created by PhpStorm.
 * User: maxencebeno
 * Date: 11/05/2016
 * Time: 09:35
 */

namespace Geolocation\AdminBundle\Domain\Services;


use Doctrine\Bundle\DoctrineBundle\Registry;

class GenerateArrayRessources
{
    /** @var  Registry */
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function generate($users, $requestCodeNaf)
    {
        $em = $this->doctrine->getManager();

        if ($idCpf = $requestCodeNaf) {

            $cpf = $em->getRepository('GeolocationAdminBundle:Cpf')
                ->findOneBy([
                    'id' => $idCpf
                ]);

            if ($cpf !== null) {
                $ressources = array();
                foreach ($users as $user) {
                    $besoin = $em->getRepository('GeolocationAdminBundle:Ressources')
                        ->findOneBy(array('user' => $user->getId(), 'besoin' => true, 'cpf' => $cpf));
                    $proposition = $em->getRepository('GeolocationAdminBundle:Ressources')
                        ->findOneBy(array('user' => $user->getId(), 'besoin' => false, 'cpf' => $cpf));

                    if ($besoin !== null || $proposition !== null) {
                        $ressources[$user->getId()] =
                            array('besoin' => $besoin,
                                'proposition' => $proposition,
                                'user' => $user
                            );
                    }
                    $adresses = $em->getRepository('GeolocationAdminBundle:Adresse')
                        ->findBy([
                            'user' => $user,
                            'isPublic' => true
                        ]);
                    if (count($adresses) > 0) {
                        foreach ($adresses as $adress) {
                            $besoinSite = $em->getRepository('GeolocationAdminBundle:Ressources')
                                ->findOneBy(array('user' => $user->getId(), 'besoin' => true, 'cpf' => $cpf, 'adresse_id' => $adress));
                            $propositionSite = $em->getRepository('GeolocationAdminBundle:Ressources')
                                ->findOneBy(array('user' => $user->getId(), 'besoin' => false, 'cpf' => $cpf, 'adresse_id' => $adress));
                            if ($besoin !== null || $propositionSite !== null) {
                                $ressources[$user->getId()]['sites'][] = [
                                    'adresse' => $adress,
                                    'besoin' => $besoinSite,
                                    'proposition' => $propositionSite
                                ];
                            }
                        }
                    }
                }
            }
        } else {

            $ressources = array();
            foreach ($users as $user) {
                $besoin = $em->getRepository('GeolocationAdminBundle:Ressources')
                    ->findOneBy(array('user' => $user->getId(), 'besoin' => true));
                $proposition = $em->getRepository('GeolocationAdminBundle:Ressources')
                    ->findOneBy(array('user' => $user->getId(), 'besoin' => false));
                $ressources[$user->getId()] =
                    array('besoin' => $besoin,
                        'proposition' => $proposition,
                        'user' => $user
                    );
                $adresses = $em->getRepository('GeolocationAdminBundle:Adresse')
                    ->findBy([
                        'user' => $user
                    ]);
                if (count($adresses) > 0) {
                    foreach ($adresses as $adress) {
                        $besoinSite = $em->getRepository('GeolocationAdminBundle:Ressources')
                            ->findOneBy(array('user' => $user->getId(), 'besoin' => true, 'adresse_id' => $adress));
                        $propositionSite = $em->getRepository('GeolocationAdminBundle:Ressources')
                            ->findOneBy(array('user' => $user->getId(), 'besoin' => false, 'adresse_id' => $adress));
                        if ($besoinSite !== null || $propositionSite !== null) {
                            $ressources[$user->getId()]['sites'][] = [
                                'adresse' => $adress,
                                'besoin' => $besoinSite,
                                'proposition' => $propositionSite
                            ];
                        }
                    }
                }
            }
        }
        return $ressources;
    }
}