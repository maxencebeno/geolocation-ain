<?php
/**
 * Created by PhpStorm.
 * User: maxencebeno
 * Date: 04/04/2016
 * Time: 17:09
 */

namespace Geolocation\AdminBundle\Domain\Services;


use Doctrine\Bundle\DoctrineBundle\Registry;

class FilterByNomEntreprise
{
    /** @var  Registry */
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * On enlève les cases du tableau ne correspondant pas au nom d'entreprise ou de site de production recherché
     *
     * @param array           $datas      Le tableau contenant toutes les entreprises
     * @param array           $request    The POST parameters
     */
    public function filterByNomEntreprise($datas = [], $nomEntreprise)
    {
        foreach ($datas as $idUser => $data) {
            $removeUser = false;
            $user = $this->doctrine->getRepository('GeolocationAdminBundle:User')
                ->findOneBy([
                    'id' => $idUser
                ]);

            if ($user !== null) {
                if (trim(strtolower($data['adresse']->getNom())) != trim(strtolower($nomEntreprise))) {
                    $removeUser = true;
                }
                if (isset($data['sites'])) {
                    foreach ($data['sites'] as $key => $site) {
                        if (trim(strtolower($site['adresse']->getNom())) == trim(strtolower($nomEntreprise))) {
                            $removeUser = false;
                        } else {
                            unset($datas[$idUser]['sites'][$key]);
                        }
                    }
                }
            }

            if ($removeUser === true) {
                unset($datas[$idUser]);
            }
        }

        return $datas;
    }
}