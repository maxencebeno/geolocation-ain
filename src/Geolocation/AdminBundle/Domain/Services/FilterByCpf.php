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
    
    public function filterByCpf(array $datas = [], Request $request) {

        if ($request->request->get('section') === "-1") {
            $cpf = $this->doctrine->getRepository('GeolocationAdminBundle:Cpf')->findAll();
        } else {
            $cpf = $this->doctrine->getRepository('GeolocationAdminBundle:Cpf')
                ->findBy([
                    'section' => $request->request->get('section'),
                    'groupe' => $request->request->get('groupe'),
                    'division' => $request->request->get('division')
                ]);
        }

        /** @var Cpf $value */
        foreach ($cpf as $value) {
            $ressources = $this->doctrine->getRepository('GeolocationAdminBundle:Ressources')
                ->findBy([
                    'cpf' => $value->getId()
                ]);

            if (count($ressources) > 0 && count($datas) === 0) {
                /** @var Ressources $ressource */
                foreach ($ressources as $ressource) {
                    if (!isset($datas[$ressource->getUser()->getId()])) {
                        $datas[$ressource->getUser()->getId()] = [];
                    }
                }
            }
        }

        foreach ($datas as $key => $data) {
            $user = $this->doctrine->getRepository('GeolocationAdminBundle:User')
                ->findOneBy([
                    'id' => $key
                ]);

            if ($user !== null) {
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

                $datas[$key] = [
                    'besoin' => $besoin,
                    'proposition' => $proposition,
                    'user' => $user
                ];
            }
        }

        return $datas;
    }
}