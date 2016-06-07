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
use Geolocation\AdminBundle\Entity\Site;
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
     * @param array $datas Le tableau contenant toutes les entreprises
     * @param array $request The POST parameters
     */
    public function filterByCodePostal($datas = [], Request $request)
    {
        foreach ($datas as $key => $data) {
            /** @var User $user */
            $user = $data['user'];

            if (substr($user->getCodePostal(), 0, 2) != substr($request->request->get('cp'), 0, 2)) {
                $datas[$key]['display'] = false;
            }
            if (isset($data['sites'])) {
                /**
                 * @var string $keySite
                 * @var Site $site ['adresse']
                 */
                foreach ($data['sites'] as $keySite => $site) {
                    if ($site !== null) {
                        if (strtolower(ApiLib::slugifyCity($site['adresse']->getCodePostal())) !== strtolower(ApiLib::slugifyCity($request->request->get('cp')))) {
                            unset($datas[$key]['sites'][$keySite]);
                        }
                    }
                }
            }
        }
        return $datas;
    }
}