<?php
/**
 * Created by PhpStorm.
 * User: maxencebeno
 * Date: 04/04/2016
 * Time: 17:09
 */

namespace Geolocation\AdminBundle\Domain\Services;


use Doctrine\Bundle\DoctrineBundle\Registry;
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
        $users = $this->doctrine->getRepository('GeolocationAdminBundle:User')
            ->findBy([
                'nom' => $request->request->get('entreprise')
            ]);

        if (count($users) > 0) {
            foreach ($users as $user) {
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
            }

        }
        
        return $datas;
    }
}