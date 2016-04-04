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

    public function filterByCodePostal ($datas = [], Request $request) {
        foreach ($datas as $key => $data) {
            /** @var User $user */
            $user = $data['user'];

            $ville = $this->doctrine->getRepository('GeolocationAdminBundle:VilleFrance')
                ->findOneBy([
                    'villeNomReel' => ApiLib::slugifyCity($user->getVille())
                ]);

            if ($ville !== null && strpos($ville->getVilleCodePostal(), $request->request->get('cp')) === false) {
                unset($datas[$key]);
            }
        }
        
        return $datas;

    }
}