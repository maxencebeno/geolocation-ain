<?php
/**
 * Created by PhpStorm.
 * User: maxencebeno
 * Date: 04/04/2016
 * Time: 17:09
 */

namespace Geolocation\AdminBundle\Domain\Services;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Geolocation\AdminBundle\Entity\Adresse;
use Geolocation\AdminBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class FilterByNomEntreprise
{
    /** @var  Registry */
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function filterByNomEntreprise($datas = [], Request $request)
    {
        /** @var Adresse $siteDeProduction */
        /** @var User $user */
        $user = $this->doctrine->getRepository('GeolocationAdminBundle:User')
            ->findEntrepriseCustom($request->request->get('entreprise'), 1);

        if ($user !== null) {

            $besoin = $this->doctrine->getRepository('GeolocationAdminBundle:Ressources')
                ->findOneBy([
                    'user' => $user,
                    'besoin' => true
                ]);

            $proposition = $this->doctrine->getRepository('GeolocationAdminBundle:Ressources')
                ->findOneBy([
                    'user' => $user,
                    'besoin' => false
                ]);

            $datas[$user->getId()] = [
                'besoin' => $besoin,
                'proposition' => $proposition,
                'user' => $user
            ];

            foreach ($datas as $idUser => $data) {
                if ($idUser !== $user->getId()) {
                    unset($datas[$idUser]);
                }
            }
        } else {
            $siteDeProduction = $this->doctrine->getRepository('GeolocationAdminBundle:Adresse')
                ->findEntrepriseCustom($request->request->get('entreprise'), 1);


            if ($siteDeProduction !== null) {
                $besoin = $this->doctrine->getRepository('GeolocationAdminBundle:Ressources')
                    ->findOneBy([
                        'adresse_id' => $siteDeProduction,
                        'besoin' => true
                    ]);

                $proposition = $this->doctrine->getRepository('GeolocationAdminBundle:Ressources')
                    ->findOneBy([
                        'adresse_id' => $siteDeProduction,
                        'besoin' => false
                    ]);

                $user = $siteDeProduction->getUser();

                if (!isset($datas[$user->getId()])) {
                    $datas[$user->getId()] = ['besoin' => null,
                        'proposition' => null,
                        'user' => $user,
                        'adresse' => $siteDeProduction
                    ];
                }
                if (!isset($datas[$user->getId()]['sites'])) {
                    $datas[$user->getId()]['sites'] = [];
                }

                $datas[$user->getId()]['sites'][] = [
                    'besoin' => $besoin,
                    'proposition' => $proposition,
                    'adresse' => $siteDeProduction
                ];
                foreach ($datas as $idUser => $data) {
                    if ($idUser !== $siteDeProduction->getUser()->getId()) {
                        unset($datas[$idUser]);
                    }
                }
            }
        }

        return $datas;
    }
}