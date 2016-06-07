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

    /**
     * On enlève les cases du tableau ne correspondant pas au cpf recherché
     *
     * @param array $datas Le tableau contenant toutes les entreprises
     * @param array $request The POST parameters
     */
    public function filterByCpf(array $datas = [], Request $request)
    {
        $cpf = $this->doctrine->getRepository('GeolocationAdminBundle:Cpf')
            ->findByCpf([
                'section' => $request->request->get('section'),
                'groupe' => $request->request->get('groupe'),
                'division' => $request->request->get('division'),
                'classe' => $request->request->get('classe')
            ]);
        $cpfExist = [];
        foreach ($cpf as $value) {
            $cpfExist[] = $value['id'];
        }

        foreach ($datas as $idUser => $data) {
            $removeUser = true;
            $continue = true;
            if ($continue === true) {
                if ($data['besoin'] !== null) {
                    if (in_array($data['besoin']->getCpf()->getId(), $cpfExist)) {
                        $removeUser = false;
                        $continue = false;
                    }
                }
                if ($data['proposition'] !== null) {
                    if (in_array($data['proposition']->getCpf()->getId(), $cpfExist)) {
                        $removeUser = false;
                        $continue = false;
                    }
                }
            }

            if (isset($data['sites'])) {
                foreach ($data['sites'] as $key => $site) {
                    $removeSite = true;
                    if ($site['besoin'] !== null) {
                        if (in_array($site['besoin']->getCpf()->getId(), $cpfExist)) {
                            $removeSite = false;
                            $removeUser = false;
                            $datas[$idUser]['display'] = false;
                        }
                    }
                    if ($site['proposition'] !== null) {
                        if (in_array($site['proposition']->getCpf()->getId(), $cpfExist)) {
                            $removeSite = false;
                            $removeUser = false;
                            $datas[$idUser]['display'] = false;
                        }
                    }
                    if ($removeSite === true) {
                        unset($datas[$idUser]['sites'][$key]);
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