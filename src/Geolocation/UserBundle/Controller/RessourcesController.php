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

        $userId = $this->getUser()->getId();
         $ressourcesProposition = $em->getRepository('GeolocationAdminBundle:Ressources')
                        ->findBy(array('user' => $userId, 'adresse_id'=>NULL, 'besoin'=>false));
         $ressourcesBesoin = $em->getRepository('GeolocationAdminBundle:Ressources')
                        ->findBy(array('user' => $userId, 'adresse_id'=>NULL, 'besoin'=>true));



        if ($formRessource->isValid()) {
            $user = $this->getUser();

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

            /* $classe = $em->getRepository('GeolocationAdminBundle:Classe')
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
              )); */

            $cpf = $em->getRepository('GeolocationAdminBundle:Cpf')
                    ->findOneBy(array(
                'section' => $section,
                'division' => $division,
                'groupe' => $groupe
            ));

            /* $cpf = $em->getRepository('GeolocationAdminBundle:Cpf')
              ->findOneBy(array(
              'section' => $section,
              'division' => $division,
              'groupe' => $groupe,
              'classe' => $classe,
              'categorie' => $categorie,
              'souscategorie' => $souscategorie
              ));
             */

            if ($cpf !== null) {
                $entity->setCpf($cpf);

                $entity->setUser($user);

                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'ressources.flash.create.success');

                $ressourcesProposition = $em->getRepository('GeolocationAdminBundle:Ressources')
                        ->findBy(array('user' => $userId, 'adresse_id'=>NULL, 'besoin'=>false));
                $ressourcesBesoin = $em->getRepository('GeolocationAdminBundle:Ressources')
                        ->findBy(array('user' => $userId, 'adresse_id'=>NULL, 'besoin'=>true));
            } else {
                $this->addFlash('danger', 'ressources.flash.create.fail');
            }
            return $this->render('GeolocationUserBundle:Ressources:edit.html.twig', array(
                        'entity' => $entity,
                        'form' => $formRessource->createView(),
                        'sections' => $sections,
                        'ressourcesPropo' => $ressources,
                        'ressourcesBesoin'=> $ressourcesBesoin
            ));
        }

        // var_dump($ressources);
        // die;

        return $this->render('GeolocationUserBundle:Ressources:edit.html.twig', array(
                    'entity' => $entity,
                    'form' => $formRessource->createView(),
                    'sections' => $sections,
                    'ressourcesPropo' => $ressourcesProposition,
                    'ressourcesBesoin'=> $ressourcesBesoin
        ));
    }

    public function deleteAction(Request $request) {
        $id= $request->attributes->get('id');
        $em = $this->getDoctrine()->getManager();
        /** @var Ressources $ressource */
        $ressource = $em->getRepository('GeolocationAdminBundle:Ressources')
                ->find($id);
        
        $idAdresseForRedirection = $ressource->getAdresseId()->getId();

        $em->remove($ressource);
        $em->flush();
        if ($request->query->get('page')){
            return $this->redirectToRoute('user_edit_site',array('id'=>$idAdresseForRedirection));
        } else{
            return $this->redirectToRoute('user_ressources');
        }
    }

}
