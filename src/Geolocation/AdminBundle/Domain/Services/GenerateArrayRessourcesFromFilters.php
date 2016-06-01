<?php
/**
 * Created by PhpStorm.
 * User: maxencebeno
 * Date: 01/06/2016
 * Time: 15:21
 */

namespace Geolocation\AdminBundle\Domain\Services;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Geolocation\AdminBundle\Entity\Adresse;
use Geolocation\AdminBundle\Entity\Ressources;
use Geolocation\AdminBundle\Entity\VilleFrance;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class GenerateArrayRessourcesFromFilters
{
    /** @var  Registry */
    private $doctrine;
    /** @var FilterByCity $cityService */
    private $cityService;
    /** @var FilterByCodePostal $cpService */
    private $cpService;
    /** @var FilterByCpf $cpfService */
    private $cpfService;
    /** @var FilterByNomEntreprise $entrepriseService */
    private $entrepriseService;
    /** @var  AuthorizationChecker $security */
    private $security;
    /** @var  TokenStorage $tokenStorage */
    private $tokenStorage;

    /**
     * GenerateArrayRessourcesFromFilters constructor.
     * @param Registry $doctrine
     * @param FilterByCity $cityService
     * @param FilterByCodePostal $cpService
     * @param FilterByCpf $cpfService
     * @param FilterByNomEntreprise $entrepriseService
     * @param AuthorizationChecker $security
     * @param TokenStorage $tokenStorage
     */
    public function __construct(Registry $doctrine, FilterByCity $cityService, FilterByCodePostal $cpService, FilterByCpf $cpfService, FilterByNomEntreprise $entrepriseService, AuthorizationChecker $security, TokenStorage $tokenStorage)
    {
        $this->doctrine = $doctrine;
        $this->cityService = $cityService;
        $this->cpService = $cpService;
        $this->cpfService = $cpfService;
        $this->entrepriseService = $entrepriseService;
        $this->security = $security;
        $this->tokenStorage = $tokenStorage;
    }


    public function generate(Request $request)
    {
        $datas = [];
        $done = [];
        $ressources = $this->doctrine->getRepository('GeolocationAdminBundle:Ressources')
            ->findAll();

        /** @var Ressources $ressource */
        foreach ($ressources as $ressource) {
            if (!in_array($ressource->getId(), $done)) {
                if (!isset($datas[$ressource->getUser()->getId()])) {
                    $datas[$ressource->getUser()->getId()] = [
                        'besoin' => null,
                        'proposition' => null,
                        'user' => null,
                        'adresse' => null,
                        'sites' => null
                    ];
                }
                if (!isset($datas[$ressource->getUser()->getId()]['sites'])) {
                    $datas[$ressource->getUser()->getId()]['sites'] = [];
                }

                if ($ressource->getBesoin() === true) {
                    $datas[$ressource->getUser()->getId()]['besoin'] = $ressource;
                }
                if ($ressource->getBesoin() === false) {
                    $datas[$ressource->getUser()->getId()]['proposition'] = $ressource;
                }
                $datas[$ressource->getUser()->getId()]['user'] = $ressource->getUser();

                $mainAddress = $this->doctrine->getRepository('GeolocationAdminBundle:Adresse')
                    ->findOneBy([
                        'user' => $ressource->getUser(),
                        'main' => true
                    ]);
                
                $datas[$ressource->getUser()->getId()]['adresse'] = $mainAddress;

                $sites = $this->doctrine->getRepository('GeolocationAdminBundle:Adresse')
                    ->findBy([
                        'user' => $ressource->getUser(),
                        'main' => false
                    ]);

                if (count($sites) > 0) {
                    /** @var Adresse $site */
                    foreach ($sites as $site) {
                        $ressourcesSite = $this->doctrine->getRepository('GeolocationAdminBundle:Ressources')
                            ->findBy([
                                'user' => $site->getUser(),
                                'adresse_id' => $site
                            ]);

                        if (count($ressourcesSite) > 0) {
                            /** @var Ressources $ressourceSite */
                            foreach ($ressourcesSite as $ressourceSite) {
                                if (!in_array($ressourceSite->getId(), $done)) {
                                    if (!isset($datas[$ressourceSite->getUser()->getId()])) {
                                        $datas[$ressourceSite->getUser()->getId()] = [
                                            'besoin' => null,
                                            'proposition' => null,
                                            'user' => null,
                                            'adresse' => null,
                                            'sites' => null
                                        ];
                                    }
                                    if (!isset($datas[$ressourceSite->getUser()->getId()]['sites'])) {
                                        $datas[$ressourceSite->getUser()->getId()]['sites'] = [];
                                    }

                                    $datas[$ressourceSite->getUser()->getId()]['sites'][] = [
                                        'besoin' => $ressourceSite->getBesoin() === true ? $ressourceSite : null,
                                        'proposition' => $ressourceSite->getBesoin() === false ? $ressourceSite : null,
                                        'adresse' => $site
                                    ];
                                    $done[] = $ressourceSite->getId();
                                }
                            }
                        }
                    }
                }
            }
            $done[] = $ressource->getId();
        }

        if ($request->request->get('entreprise') !== "") {
            $datas = $this->entrepriseService->filterByNomEntreprise($datas, $request->request->get('entreprise'));
        }

        if ($request->request->get('section') !== "-1") {
            $datas = $this->cpfService->filterByCpf($datas, $request);
        }

        if ($request->request->get('cp') !== "") {
            $datas = $this->cpService->filterByCodePostal($datas, $request);
        }

        if ($request->request->get('city')) {
            /** @var VilleFrance $ville */
            $ville = $this->doctrine->getRepository('GeolocationAdminBundle:VilleFrance')->findOneBy([
                'villeNom' => $request->request->get('city')
            ]);

            $datas = $this->cityService->filterByCity($datas, $request);

            if ($ville !== null) {
                $datas['ville'] = [
                    'lat' => $ville->getVilleLatitudeDeg(),
                    'lng' => $ville->getVilleLongitudeDeg()
                ];
            }
        }
        $auth_checker = $this->security;

        if ($auth_checker->isGranted("IS_AUTHENTICATED_REMEMBERED") || $auth_checker->isGranted("IS_AUTHENTICATED_FULLY")) {
            $datas['connectedUser'] = $this->tokenStorage->getToken()->getUser();
        }

        return $datas;
    }
}