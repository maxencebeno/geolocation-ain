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

    public function filterByCity($datas = [], Request $request)
    {
        foreach ($datas as $key => $data) {
            /** @var User $user */
            $user = $data['user'];

            if (strtolower(ApiLib::slugifyCity($user->getVille())) !== strtolower(ApiLib::slugifyCity($request->request->get('city')))) {
                if (isset($data['sites'])) {
                    foreach ($data['sites'] as $sites) {
                        foreach ($sites as $site) {
                            if ($site !== null && get_class($site) === "Adresse") {
                                if (strtolower(ApiLib::slugifyCity($site->getVille())) !== strtolower(ApiLib::slugifyCity($request->request->get('city')))) {
                                    unset($datas[$key]);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $datas;
    }
}