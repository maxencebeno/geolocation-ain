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
                'division' => $request->request->get('division'),
                'classe' => $request->request->get('classe')
            ]);

        foreach ($datas as $idUser => $data) {
            $removeUser = true;
            $continue = true;
            /** @var Cpf $item */
            foreach ($cpf as $item) {
                if ($continue === true) {
                    if ($data['besoin'] !== null && $continue === true) {
                        if ($item->getId() === $data['besoin']->getCpf()->getId()) {
                            $removeUser = false;
                            $continue = false;
                        }
                    }
                    if ($data['proposition'] !== null && $continue === true) {
                        if ($item->getId() === $data['proposition']->getCpf()->getId()) {
                            $removeUser = false;
                            $continue = false;
                        }
                    }
                }
            }

            if (isset($data['sites'])) {
                foreach ($data['sites'] as $key => $site) {
                    $removeSite = true;
                    $continue = true;
                    /** @var Cpf $item */
                    foreach ($cpf as $item) {
                        if ($continue === true) {
                            if ($site['besoin'] !== null && $continue === true) {
                                if ($item->getId() === $site['besoin']->getCpf()->getId()) {
                                    $removeSite = false;
                                    $continue = false;
                                }
                            }
                            if ($site['proposition'] !== null && $continue === true) {
                                if ($item->getId() === $site['proposition']->getCpf()->getId()) {
                                    $removeSite = false;
                                    $continue = false;
                                }
                            }
                        }
                        if ($removeSite === true) {
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