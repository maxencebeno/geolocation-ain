<?php

namespace Geolocation\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Geolocation\AdminBundle\Entity\Ressources;
use Geolocation\AdminBundle\Form\RessourcesType;

class RessourcesController extends Controller {

    public function editAction(Request $request) {
        // On récupère les infos pour le formulaire en ajax
        $em = $this->getDoctrine()->getManager();

        $sections = $em->getRepository('GeolocationAdminBundle:Section')
            ->findAll();
        $entity = new Ressources();

        $formRessource = $this->createForm(new RessourcesType(), $entity);
        $formRessource->handleRequest($request);
        
        $ressources = $em->getRepository('GeolocationAdminBundle:Ressources')
            ->findAll();
        

        if ($formRessource->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

            return $this->redirect($this->generateUrl('ressources_show', array('id' => $entity->getId())));
        }

       // var_dump($ressources);
       // die;

        return $this->render('GeolocationUserBundle:Ressources:edit.html.twig', array(
                    'entity' => $entity,
                    'form' => $formRessource->createView(),
                    'sections' => $sections,
                    'ressources'=> $ressources
        ));
    }

}
