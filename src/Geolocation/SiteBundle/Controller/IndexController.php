<?php

namespace Geolocation\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->render('SiteBundle:Index:index.html.twig');
    }
    
    public function getCitiesAction(Request $request)
    {
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
}



