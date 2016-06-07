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
use Geolocation\AdminBundle\Entity\Site;
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
    /** @var  CalculateDistanceFromPosition $distanceService */
    private $distanceService;

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
    public function __construct(Registry $doctrine, FilterByCity $cityService, FilterByCodePostal $cpService, FilterByCpf $cpfService, FilterByNomEntreprise $entrepriseService, AuthorizationChecker $security, TokenStorage $tokenStorage, CalculateDistanceFromPosition $distanceService)
    {
        $this->doctrine = $doctrine;
        $this->cityService = $cityService;
        $this->cpService = $cpService;
        $this->cpfService = $cpfService;
        $this->entrepriseService = $entrepriseService;
        $this->security = $security;
        $this->tokenStorage = $tokenStorage;
        $this->distanceService = $distanceService;
    }

    /**
     * Construction du tableau des ressources
     *
     * Exemple :
     *
     * $ressources = [
     *      besoin => [Tableau d'objet de besoin],
     *      proposition => [Tableau d'objet de proposition],
     *      user => [L'entreprise concernée (le user)],
     *      adresse => [L'adresse de son entreprise],
     *      sites => [
     *          besoin => [Tableau d'objet de besoin],
     *          proposition => [Tableau d'objet de proposition],
     *          adresse => [L'adresse du site de production],
     *      ]
     * ]
     *
     * La fonction génère tout le tableaux de ressources en prenant en compte les filtres de la page d'accueil
     * Les filtres sont gérés dans les différents services de ce même dossier
     *
     * @param array $request variables POST
     *
     */
    public function generate(Request $request)
    {
        $auth_checker = $this->security;
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
                        'sites' => null,
                        'display' => true
                    ];
                }
                if (!isset($datas[$ressource->getUser()->getId()]['sites'])) {
                    $datas[$ressource->getUser()->getId()]['sites'] = [];
                }

                if ($ressource->getBesoin() === true) {
                    $datas[$ressource->getUser()->getId()]['besoin'] = $ressource;
                } else {
                    $datas[$ressource->getUser()->getId()]['proposition'] = $ressource;
                }
                $datas[$ressource->getUser()->getId()]['user'] = $ressource->getUser();

                $mainAddress = $this->doctrine->getRepository('GeolocationAdminBundle:Site')
                    ->findOneBy([
                        'user' => $ressource->getUser(),
                        'main' => true
                    ]);

                $datas[$ressource->getUser()->getId()]['adresse'] = $mainAddress;

                $sites = $this->doctrine->getRepository('GeolocationAdminBundle:Site')
                    ->findBy([
                        'user' => $ressource->getUser(),
                        'main' => false
                    ]);

                if (count($sites) > 0) {
                    /** @var Site $site */
                    foreach ($sites as $site) {
                        $ressourcesSite = $this->doctrine->getRepository('GeolocationAdminBundle:Ressources')
                            ->findBy([
                                'user' => $site->getUser(),
                                'site' => $site
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
                                            'sites' => null,
                                            'display' => true
                                        ];
                                    }
                                    if (!isset($datas[$ressourceSite->getUser()->getId()]['sites'])) {
                                        $datas[$ressourceSite->getUser()->getId()]['sites'] = [
                                            'besoin' => null,
                                            'proposition' => null,
                                            'adresse' => null
                                        ];
                                    }
                                    $arraySite = [];

                                    if ($ressourceSite->getBesoin() === true) {
                                        $arraySite['besoin'] = $ressourceSite;
                                        $arraySite['proposition'] = null;
                                    } else {
                                        $arraySite['proposition'] = $ressourceSite;
                                        $arraySite['besoin'] = null;
                                    }
                                    $arraySite['adresse'] = $site;
                                    $datas[$ressourceSite->getUser()->getId()]['sites'][] = $arraySite;

                                    $done[] = $ressourceSite->getId();
                                }
                            }
                        }
                    }
                }
            }
            $done[] = $ressource->getId();
        }

        /* On a tout trié, maintenant on calcule les distances entre chaque entreprises et sites de production
         * On peut faire ça seulement si l'utilisateur est connecté puisqu'un a au moins un point de repère
         * pour calculer les distances (son entreprise mère et ses sites de production
        */

        $user = $this->tokenStorage->getToken()->getUser();

        if ($auth_checker->isGranted("IS_AUTHENTICATED_REMEMBERED") || $auth_checker->isGranted("IS_AUTHENTICATED_FULLY")) {
            $ressourceUser = null;

            foreach ($datas as $idUser => $ressource) {
                if ($idUser === $user->getId()) {
                    $ressourceUser = $ressource;
                }
            }

            if ($ressourceUser !== null) {
                /** @var Site $entrepriseMereUser */
                $entrepriseMereUser = $ressourceUser['adresse'];
                $siteDeProductionUser = $ressourceUser['sites'];

                foreach ($datas as $idUser => $ressource) {
                    if ($idUser !== $user->getId()) {
                        $distance = $this->distanceService->getDistanceFromPosition($entrepriseMereUser, $ressource['adresse']);
                        if (isset($distance['value'])) {
                            $datas[$idUser]['distances'][] = [
                                'value' => $distance['value'],
                                'text' => $distance['text'],
                                'duration' => $distance['duration'],
                                'entreprise' => $entrepriseMereUser
                            ];
                        }
                        if (isset($ressource['sites'])) {
                            foreach ($ressource['sites'] as $site) {
                                /** @var Site $destination */
                                $destination = $site['adresse'];
                                foreach ($siteDeProductionUser as $value) {
                                    /** @var Site $depart */
                                    $depart = $value['adresse'];
                                    if ($depart->getUser()->getId() !== $destination->getUser()->getId()) {
                                        $distance = $this->distanceService->getDistanceFromPosition($depart, $destination);
                                        if (isset($distance['value'])) {
                                            $datas[$idUser]['distances'][] = [
                                                'value' => $distance['value'],
                                                'text' => $distance['text'],
                                                'duration' => $distance['duration'],
                                                'entrepriseDepart' => $depart
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
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


        if ($auth_checker->isGranted("IS_AUTHENTICATED_REMEMBERED") || $auth_checker->isGranted("IS_AUTHENTICATED_FULLY")) {
            $datas['connectedUser'] = $this->tokenStorage->getToken()->getUser();
        }

        return $datas;
    }
}