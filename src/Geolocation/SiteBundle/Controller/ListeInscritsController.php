<?php

namespace Geolocation\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ListeInscritsController extends Controller {

    public function listeInscritsAction() {
        $em = $this->getDoctrine()->getManager();
        $entreprises = $em->getRepository('GeolocationAdminBundle:Adresse')
                ->findBy([],['pilier'=>"ASC"]);
      //  var_dump($entreprises);
        return $this->render('SiteBundle:ListeInscrits:listeinscrits.html.twig', 
                array('entreprises'=>$entreprises)
        );
    }

}
