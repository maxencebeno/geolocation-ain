<?php

namespace Geolocation\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DetailsController extends Controller
{
    public function detailsAction($id)
    {
    	$em = $this->getDoctrine()->getManager()->getRepository('GeolocationAdminBundle:User');
        $em2 = $this->getDoctrine()->getManager()->getRepository('GeolocationAdminBundle:Ressources');
        $em3 = $this->getDoctrine()->getManager()->getRepository('GeolocationAdminBundle:Adresse');

    	$site = $em3->findOneBy(array('id'=>$id));
        $user = $em->findOneBy(array('id'=>$site->getUser()));
        // Ressources des sites
        $ressources = $em2->findBy(array('adresse_id'=>$site->getId()));
        
        $isMain = False;
        $sites=[];
        // ID du site mère
        $main = $em3->findOneBy(array('user'=>$user->getId(), 'main' => true));
        if($site->getMain()){
            $isMain = true;
            // Sites du site mère
            $sites = $em3->findBy(array('user'=>$user->getId(), 'main' => false));
            // ressources du site mère
            $ressources = $em2->findBy(array('adresse_id'=>$main->getId()));
        }
        
        
        $cpfs = [];
        foreach ($ressources as $ressource) {
            $cpfs[$ressource->getCpf()->getId()] = [
                'categorie' => [
                    'code' => $ressource->getCpf()->getCategorie()->getCode(),
                    'libelle' => $ressource->getCpf()->getCategorie()->getLibelle(),
                    'affiche' => $ressource->getCpf()->getCategorie()->getAffiche()
                ],
                'section' => [
                    'code' => $ressource->getCpf()->getSection()->getCode(),
                    'libelle' => $ressource->getCpf()->getSection()->getLibelle(),
                    'image' => $ressource->getCpf()->getSection()->getImage(),
                    'affiche' => $ressource->getCpf()->getSection()->getAffiche()
                ],
                'classe' => [
                    'code' => $ressource->getCpf()->getClasse()->getCode(),
                    'libelle' => $ressource->getCpf()->getClasse()->getLibelle(),
                    'affiche' => $ressource->getCpf()->getClasse()->getAffiche()
                ],
                'groupe' => [
                    'code' => $ressource->getCpf()->getGroupe()->getCode(),
                    'libelle' => $ressource->getCpf()->getGroupe()->getLibelle(),
                    'affiche' => $ressource->getCpf()->getGroupe()->getAffiche()
                ],
                'division' => [
                    'code' => $ressource->getCpf()->getDivision()->getCode(),
                    'libelle' => $ressource->getCpf()->getDivision()->getLibelle(),
                    'affiche' => $ressource->getCpf()->getDivision()->getAffiche()
                ],
                'souscategorie' => [
                    'code' => $ressource->getCpf()->getSouscategorie()->getCode(),
                    'libelle' => $ressource->getCpf()->getSouscategorie()->getLibelle(),
                    'affiche' => $ressource->getCpf()->getSouscategorie()->getAffiche()
                ]
            ];
        }
        
        return $this->render('SiteBundle:Details:details.html.twig', array('user'=>$user,'site'=>$site, 'ress'=>$ressources, 'isMain'=>$isMain, 'sites'=>$sites, 'main'=>$main));
    }
}
