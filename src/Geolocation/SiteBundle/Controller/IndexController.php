<?php

namespace Geolocation\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class IndexController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('GeolocationAdminBundle:Section')
                ->findAll();

        return $this->render('SiteBundle:Index:index.html.twig', ['sections' => $sections]);
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

            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    public function loadMarkersAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $array = $em->getRepository('GeolocationAdminBundle:User')
                ->findByEnabled(1);

        $ressources = array();
        foreach ($array as $user) {
            $besoin = $em->getRepository('GeolocationAdminBundle:Ressources')
                    ->findOneBy(array('user' => $user->getId(), 'besoin'=> true));
            $proposition =  $em->getRepository('GeolocationAdminBundle:Ressources')
                    ->findOneBy(array('user' => $user->getId(), 'besoin'=> false));
            $ressources[$user->getId()] = 
                    array('besoin' => $besoin,
                        'proposition'=>$proposition,
                        'user'=>$user
                           );
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

}
