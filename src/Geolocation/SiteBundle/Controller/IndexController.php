<?php

namespace Geolocation\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class IndexController extends Controller {

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('GeolocationAdminBundle:Section')
                ->findByUserExist();
        
        $piliers = $em->getRepository('GeolocationAdminBundle:Pilier')
            ->findAll();

        return $this->render('SiteBundle:Index:index.html.twig', ['sections' => $sections, 'piliers' => $piliers]);
    }

    public function getCitiesAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $term = $request->request->get('ville');
            $array = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('GeolocationAdminBundle:VilleFrance')
                    ->findVillesLike($term);

            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    public function getCodeNafAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $term = $request->request->get('codeNaf');
            $array = $this->getDoctrine()
                ->getManager()
                ->getRepository('GeolocationAdminBundle:Cpf')
                ->findCodeNafLike($term);

            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    public function getRessourcesAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $term = $request->request->get('ressources');
            $array = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('GeolocationAdminBundle:Categorie')
                    ->findRessourcesLike($term);

            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    public function getEntrepriseAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $term = $request->request->get('entreprise');
            $array = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('GeolocationAdminBundle:User')
                    ->findEntrepriseLike($term);

            if (count($array) === 0) {
                $array = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('GeolocationAdminBundle:Adresse')
                    ->findEntrepriseLike($term);
            }

            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    public function loadMarkersAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $array = $em->getRepository('GeolocationAdminBundle:User')
                ->findBy([
                    'enabled' => true
                ]);

        $generationService = $this->get('site_bundle.generate_array_ressources');

        $codeNaf = $request->attributes->get('codeNaf');

        $ressources = $generationService->generate($array, $codeNaf);

        $user = $this->getUser();

        $auth_checker = $this->get('security.authorization_checker');

        if ($auth_checker->isGranted("IS_AUTHENTICATED_REMEMBERED") || $auth_checker->isGranted("IS_AUTHENTICATED_FULLY")) {
            $ressources['connectedUser'] = $user;
            
            /*$distanceService = $this->get('site_bundle.calculate_distance_from_position');

            $minDistance = $distanceService->getMinDistance($ressources, $user);
            $maxDistance = $distanceService->getMaxDistance($ressources, $user);*/
            
            $ressources['distances'] = [
                'min' => 0,
                'max' => 1000
            ];
        }

        $ignoredAttributes = array('user');
        $normalizer = new GetSetMethodNormalizer();
        $normalizer->setIgnoredAttributes($ignoredAttributes);
        $serializer = new Serializer(array($normalizer), array('ressources' => new JsonEncoder()));
        $jsonContent = $serializer->serialize($ressources, 'json');  
        
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function getCodepostalFromCityAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $array = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('GeolocationAdminBundle:VilleFrance')
                    ->findCodePostalFromCity($request->request->get('city'));

            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }
    
    public function resetFiltersAction () {
        return $this->redirectToRoute('site_homepage');
    }

}
