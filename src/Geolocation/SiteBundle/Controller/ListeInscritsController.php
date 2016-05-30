<?php

namespace Geolocation\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ListeInscritsController extends Controller {

    public function listeInscritsAction() {
        $em = $this->getDoctrine()->getManager();
        $array = [];
        
        $piliers = $em->getRepository('GeolocationAdminBundle:Pilier')
            ->findBy([], ['nom' => 'ASC']);
        
        foreach ($piliers as $pilier) {
            if (!isset($array[$pilier->getId()])) {
                $array[$pilier->getId()] = [];
            }
            $entreprises = $em->getRepository('GeolocationAdminBundle:Adresse')
                ->findBy([
                    'pilier' => $pilier
                ], [
                    'nom' => 'ASC'
                ]);
            if (count($entreprises) > 0) {
                $array[$pilier->getId()] = ['entreprises' => $entreprises, 'pilier' => $pilier->getNom()];
            } else {
                $array[$pilier->getId()] = ['entreprises' => "Pas d'entreprise pour le moment", 'pilier' => $pilier->getNom()];
            }
        }
        
        
      //  var_dump($entreprises);
        return $this->render('SiteBundle:ListeInscrits:listeinscrits.html.twig', 
                array('datas'=>$array)
        );
    }

}
