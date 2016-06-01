<?php

namespace Geolocation\AdminBundle\Domain\Services;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Geolocation\AdminBundle\Entity\Cpf;
use Geolocation\AdminBundle\Entity\Ressources;
use Symfony\Component\HttpFoundation\Request;

class FilterByCpf
{
    /** @var  Registry */
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function filterByCpf(array $datas = [], Request $request)
    {

        $cpf = $this->doctrine->getRepository('GeolocationAdminBundle:Cpf')
            ->findByCpf([
                'section' => $request->request->get('section'),
                'groupe' => $request->request->get('groupe'),
                'division' => $request->request->get('division')
            ]);

        foreach ($datas as $idUser => $data) {
            $removeUser = false;
            /** @var Cpf $value */
            foreach ($cpf as $value) {
                if ($data['besoin']->getId() !== $value->getId() && $data['proposition']->getId() !== $value->getId()) {
                    $removeUser = true;
                }
                if (isset($data['sites'])) {
                    $removeUser = false;
                    foreach ($data['sites'] as $key => $site) {
                        if ($site['besoin']->getId() !== $value->getId() && $site['proposition']->getId() !== $value->getId()) {
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