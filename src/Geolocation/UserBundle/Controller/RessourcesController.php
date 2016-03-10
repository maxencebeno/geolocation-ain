<?php

namespace Geolocation\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Geolocation\AdminBundle\Entity\Ressources;
use Geolocation\AdminBundle\Form\RessourcesType;

class RessourcesController extends Controller
{

    public function editAction(Request $request)
    {
        // On récupère les infos pour le formulaire en ajax
        $em = $this->getDoctrine()->getManager();

        $sections = $em->getRepository('GeolocationAdminBundle:Section')
            ->findAll();
        $entity = new Ressources();

        $formRessource = $this->createForm(new RessourcesType(), $entity);
        $formRessource->handleRequest($request);

        $userId = $this->getUser()->getId();
        $ressources = $em->getRepository('GeolocationAdminBundle:Ressources')
            ->findBy(array('user'=> $userId));

       

        if ($formRessource->isValid()) {
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $section = $em->getRepository('GeolocationAdminBundle:Section')
                ->findOneBy(array(
                    'id' => $request->request->get('section')
                ));

            $division = $em->getRepository('GeolocationAdminBundle:Division')
                ->findOneBy(array(
                    'id' => $request->request->get('division')
                ));

            $groupe = $em->getRepository('GeolocationAdminBundle:Groupe')
                ->findOneBy(array(
                    'id' => $request->request->get('groupe')
                ));

            $classe = $em->getRepository('GeolocationAdminBundle:Classe')
                ->findOneBy(array(
                    'id' => $request->request->get('classe')
                ));

            $categorie = $em->getRepository('GeolocationAdminBundle:Categorie')
                ->findOneBy(array(
                    'id' => $request->request->get('categorie')
                ));

            $souscategorie = $em->getRepository('GeolocationAdminBundle:SousCategorie')
                ->findOneBy(array(
                    'id' => $request->request->get('souscategorie')
                ));

            $cpf = $em->getRepository('GeolocationAdminBundle:Cpf')
                ->findOneBy(array(
                    'section' => $section,
                    'division' => $division,
                    'groupe' => $groupe,
                    'classe' => $classe,
                    'categorie' => $categorie,
                    'souscategorie' => $souscategorie
                ));

            if ($cpf !== null) {
                $entity->setCpf($cpf);
            }
            $entity->setUser($user);

            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

            return $this->render('GeolocationUserBundle:Ressources:edit.html.twig', array(
                'entity' => $entity,
                'form' => $formRessource->createView(),
                'sections' => $sections,
                'ressources' => $ressources
            ));
        }

        // var_dump($ressources);
        // die;

        return $this->render('GeolocationUserBundle:Ressources:edit.html.twig', array(
            'entity' => $entity,
            'form' => $formRessource->createView(),
            'sections' => $sections,
            'ressources' => $ressources
        ));
    }
    
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
       // $ressource = $em->getRepository('GeolocationAdminBundle:Ressources')
     //       ->findBy($id);
        
       return $this->redirectToRoute('user_ressources');
    }

}
