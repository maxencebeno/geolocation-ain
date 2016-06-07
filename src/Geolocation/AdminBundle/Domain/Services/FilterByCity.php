<?php
/**
 * Created by PhpStorm.
 * User: maxencebeno
 * Date: 04/04/2016
 * Time: 16:26
 */

namespace Geolocation\AdminBundle\Domain\Services;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Geolocation\AdminBundle\Domain\Api\ApiLib;
use Geolocation\AdminBundle\Entity\Adresse;
use Geolocation\AdminBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class FilterByCity
{
    /** @var  Registry */
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * On enlève les cases du tableau ne correspondant pas à la ville cherchée
     *
     * @param array $datas Le tableau contenant toutes les entreprises
     * @param array $request The POST parameters
     */
    public function filterByCity($datas = [], Request $request)
    {
        foreach ($datas as $key => $data) {
            /** @var User $user */
            $user = $data['user'];

            if (strtolower(ApiLib::slugifyCity($user->getVille())) !== strtolower(ApiLib::slugifyCity($request->request->get('city')))) {
                $datas[$key]['display'] = false;
            }
            if (isset($data['sites'])) {
                foreach ($data['sites'] as $keySite => $site) {
                    if ($site !== null) {
                        if (strtolower(ApiLib::slugifyCity($site['adresse']->getVille())) !== strtolower(ApiLib::slugifyCity($request->request->get('city')))) {
                            unset($datas[$key]['sites'][$keySite]);
                        }
                    }
                }
            }
        }

        return $datas;
    }
}