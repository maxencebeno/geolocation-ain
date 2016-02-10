<?php

namespace Geolocation\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RessourcesController extends Controller {

    public function editAction(Request $request) {
        // On récupère les infos pour le formulaire en ajax
        $em = $this->getDoctrine()->getManager();

        $sections = $em->getRepository('GeolocationAdminBundle:Section')
            ->findAll();
        $entity = new Ressources();
        $formRessource = $this->createForm(new RessourcesType(), $entity);
        $formRessource->bind($request);

        if ($formRessource->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

            return $this->redirect($this->generateUrl('ressources_show', array('id' => $entity->getId())));
        }


        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
                    'entity' => $entity,
                    'formRessource' => $formRessource->createView(),
                    'sections' => $sections
        ));
    }

}
