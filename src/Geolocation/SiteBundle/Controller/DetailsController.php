<?php

namespace Geolocation\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DetailsController extends Controller
{
    public function detailsAction($id)
    {
    	$em = $this->getDoctrine()->getManager()->getRepository('GeolocationAdminBundle:User');
        $em2 = $this->getDoctrine()->getManager()->getRepository('GeolocationAdminBundle:Ressources');

    	$user = $em->findOneBy(array('id'=>$id));
        $ressources = $em2->findBy(array('user'=>$user->getId()));
        
        $cpfs = [];
        foreach ($ressources as $ressource) {
            var_dump($ressource->getId());
            var_dump($ressource->getCpf()->getCategorie()->getCode());
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
        die();
        
        return $this->render('SiteBundle:Details:details.html.twig', array('user'=>$user, 'ress'=>$ressources));
    }
}
