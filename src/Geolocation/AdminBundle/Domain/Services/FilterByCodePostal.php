<?php
/**
 * Created by PhpStorm.
 * User: maxencebeno
 * Date: 04/04/2016
 * Time: 16:35
 */

namespace Geolocation\AdminBundle\Domain\Services;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Geolocation\AdminBundle\Domain\Api\ApiLib;
use Geolocation\AdminBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class FilterByCodePostal
{
    /** @var  Registry */
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * On enlève les cases du tableau ne correspondant pas au code postal recherché
     *
     * @param array           $datas      Le tableau contenant toutes les entreprises
     * @param array           $request    The POST parameters
     */
    public function filterByCodePostal ($datas = [], Request $request) {
        foreach ($datas as $key => $data) {
            /** @var User $user */
            $user = $data['user'];

            $ville = $this->doctrine->getRepository('GeolocationAdminBundle:VilleFrance')
                ->findOneBy([
                    'villeNom' => ApiLib::slugifyCity($user->getVille())
                ]);

            if ($ville !== null && substr($ville->getVilleCodePostal(), 0, 2) != substr($request->request->get('cp'), 0, 2)) {
                unset($datas[$key]);
            }
        }
        
        return $datas;

    }
}