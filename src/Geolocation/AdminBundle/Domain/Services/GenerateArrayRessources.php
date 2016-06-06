<?php
/**
 * Created by PhpStorm.
 * User: maxencebeno
 * Date: 11/05/2016
 * Time: 09:35
 */

namespace Geolocation\AdminBundle\Domain\Services;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Geolocation\AdminBundle\Entity\Site;
use Geolocation\AdminBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class GenerateArrayRessources
{
    /** @var  Registry */
    private $doctrine;
    /** @var  CalculateDistanceFromPosition $distanceService */
    private $distanceService;
    /** @var  AuthorizationChecker $security */
    private $security;
    /** @var  TokenStorage $tokenStorage */
    private $tokenStorage;

    public function __construct($doctrine, CalculateDistanceFromPosition $distanceService, AuthorizationChecker $security, TokenStorage $tokenStorage)
    {
        $this->doctrine = $doctrine;
        $this->distanceService = $distanceService;
        $this->security = $security;
        $this->tokenStorage = $tokenStorage;
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
     * @param array $users Tous les utilisateurs (entreprises)
     * @param string $requestCodeNaf Paramètre optionel de recherche sur un code NAF en particulier
     *
     */
    public function generate($users, $requestCodeNaf)
    {
        $ressources = [];
        $em = $this->doctrine->getManager();
        if ($requestCodeNaf === "-1") {
            return [];
        }
        if ($idCpf = $requestCodeNaf) {
            $cpf = $em->getRepository('GeolocationAdminBundle:Cpf')
                ->findOneBy([
                    'id' => $idCpf
                ]);

            if ($cpf !== null) {
                /** @var User $user */
                foreach ($users as $user) {
                    $adresse = $em->getRepository('GeolocationAdminBundle:Site')
                        ->findOneBy([
                            'user' => $user->getId(),
                            'main' => true
                        ]);
                    $besoin = $em->getRepository('GeolocationAdminBundle:Ressources')
                        ->findOneBy(array('user' => $user->getId(), 'besoin' => true, 'cpf' => $cpf));
                    $proposition = $em->getRepository('GeolocationAdminBundle:Ressources')
                        ->findOneBy(array('user' => $user->getId(), 'besoin' => false, 'cpf' => $cpf));

                    if ($besoin !== null || $proposition !== null) {
                        $ressources[$user->getId()] =
                            array('besoin' => $besoin,
                                'proposition' => $proposition,
                                'user' => $user,
                                'adresse' => $adresse
                            );
                    }
                    $sites = $em->getRepository('GeolocationAdminBundle:Site')
                        ->findBy([
                            'user' => $user,
                            'isPublic' => true
                        ]);
                    if (count($sites) > 0) {
                        foreach ($sites as $site) {
                            $besoinSite = $em->getRepository('GeolocationAdminBundle:Ressources')
                                ->findOneBy(array('user' => $user->getId(), 'besoin' => true, 'cpf' => $cpf, 'site' => $site));
                            $propositionSite = $em->getRepository('GeolocationAdminBundle:Ressources')
                                ->findOneBy(array('user' => $user->getId(), 'besoin' => false, 'cpf' => $cpf, 'site' => $site));
                            if ($besoin !== null || $propositionSite !== null) {
                                $ressources[$user->getId()]['sites'][] = [
                                    'adresse' => $site,
                                    'besoin' => $besoinSite,
                                    'proposition' => $propositionSite
                                ];
                            }
                        }
                        if (isset($ressources[$user->getId()]['sites']) && count($ressources[$user->getId()]['sites']) === 0) {
                            $ressources[$user->getId()]['sites'] = null;
                        }
                    }
                }
            }
        } else {

            $ressources = array();
            foreach ($users as $user) {
                $adresse = $em->getRepository('GeolocationAdminBundle:Site')
                    ->findOneBy([
                        'user' => $user->getId(),
                        'main' => true
                    ]);
                $besoin = $em->getRepository('GeolocationAdminBundle:Ressources')
                    ->findOneBy(array('user' => $user->getId(), 'besoin' => true));
                $proposition = $em->getRepository('GeolocationAdminBundle:Ressources')
                    ->findOneBy(array('user' => $user->getId(), 'besoin' => false));
                $ressources[$user->getId()] =
                    array('besoin' => $besoin,
                        'proposition' => $proposition,
                        'user' => $user,
                        'adresse' => $adresse
                    );
                $sites = $em->getRepository('GeolocationAdminBundle:Site')
                    ->findBy([
                        'user' => $user,
                        'main' => false
                    ]);

                if (count($sites) > 0) {
                    foreach ($sites as $site) {
                        $besoinSite = $em->getRepository('GeolocationAdminBundle:Ressources')
                            ->findOneBy(array('user' => $user->getId(), 'besoin' => true, 'site' => $site));
                        $propositionSite = $em->getRepository('GeolocationAdminBundle:Ressources')
                            ->findOneBy(array('user' => $user->getId(), 'besoin' => false, 'site' => $site));
                        if ($besoinSite !== null || $propositionSite !== null) {
                            $ressources[$user->getId()]['sites'][] = [
                                'adresse' => $site,
                                'besoin' => $besoinSite,
                                'proposition' => $propositionSite
                            ];
                        }
                    }
                    if (isset($ressources[$user->getId()]['sites']) && count($ressources[$user->getId()]['sites']) === 0) {
                        $ressources[$user->getId()]['sites'] = null;
                    }
                }
            }
        }

        /* On a tout trié, maintenant on calcule les distances entre chaque entreprises et sites de production
         *  On peut faire ça seulement si l'utilisateur est connecté puisqu'un a au moins un point de repère
         * pour calculer les distances (son entreprise mère et ses sites de production
         */

        $auth_checker = $this->security;
        if ($auth_checker->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            $user = $this->tokenStorage->getToken()->getUser();

            $ressourceUser = null;

            foreach ($ressources as $idUser => $ressource) {
                if ($idUser === $user->getId()) {
                    $ressourceUser = $ressource;
                }
            }

            if ($ressourceUser !== null) {
                /** @var Site $entrepriseMereUser */
                $entrepriseMereUser = $ressourceUser['adresse'];
                $siteDeProductionUser = $ressourceUser['sites'];

                foreach ($ressources as $idUser => $ressource) {
                    if ($idUser !== $user->getId()) {
                        $distance = $this->distanceService->getDistanceFromPosition($entrepriseMereUser, $ressource['adresse']);
                        if (isset($distance['value'])) {
                            $ressources[$idUser]['distances'][] = [
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
                                            $ressources[$idUser]['distances'][] = [
                                                'value' => $distance['value'],
                                                'text' => $distance['text'],
                                                'duration' => $distance['duration'],
                                                'entreprise' => $depart
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

        return $ressources;
    }
}