<?php

namespace Geolocation\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Geolocation\AdminBundle\Entity\Ressources;
use Geolocation\AdminBundle\Form\RessourcesType;

class RessourcesController extends Controller {

    public function showAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $sections = $em->getRepository('GeolocationAdminBundle:Section')
                ->findBy(['affiche' => true]);
        $entity = new Ressources();

        //création du formulaire d'ajout de ressources
        $formRessource = $this->createForm(new RessourcesType(), $entity);
        $formRessource->handleRequest($request);

        $userId = $this->getUser()->getId();
        
        //récupération des ressources (besoin et proposition) de l'entreprise courant pour pouvoir les afficher par la suite
        $ressourcesProposition = $em->getRepository('GeolocationAdminBundle:Ressources')
                ->findBy(array('user' => $userId, 'adresse_id' => NULL, 'besoin' => false));
        $ressourcesBesoin = $em->getRepository('GeolocationAdminBundle:Ressources')
                ->findBy(array('user' => $userId, 'adresse_id' => NULL, 'besoin' => true));


        //vérification du formulaire envoyé
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
            
            //vérification que la ressource choisie existe
            if ($cpf !== null) {
                
                //vérification que la ressources n'est pas déjà ajoutée pour cette entreprise
                $existeRessource= $em->getRepository('GeolocationAdminBundle:Ressources')
                        ->findOneBy(array(
                            'user'=>$user,
                            'cpf'=>$cpf,
                            'adresse_id'=>null,
                            'besoin'=>$entity->getBesoin()
                        ));
                
                if (count($existeRessource)>0) {
                     $this->addFlash('danger', 'ressources.flash.already_exist');
                }else{    
                    //ajout en base    
                    $entity->setCpf($cpf);
                    $entity->setUser($user);
                    $em->persist($entity);
                    $em->flush();
                    $this->addFlash('success', 'ressources.flash.create.success');

                    $ressourcesProposition = $em->getRepository('GeolocationAdminBundle:Ressources')
                            ->findBy(array('user' => $userId, 'adresse_id' => NULL, 'besoin' => false));
                    $ressourcesBesoin = $em->getRepository('GeolocationAdminBundle:Ressources')
                            ->findBy(array('user' => $userId, 'adresse_id' => NULL, 'besoin' => true));
                    }
            } else {
                $this->addFlash('danger', 'ressources.flash.create.fail');
            }
            
            return $this->render('GeolocationUserBundle:Ressources:show.html.twig', array(
                        'entity' => $entity,
                        'form' => $formRessource->createView(),
                        'sections' => $sections,
                        'ressourcesPropo' => $ressourcesProposition,
                        'ressourcesBesoin' => $ressourcesBesoin
            ));
        }


        return $this->render('GeolocationUserBundle:Ressources:show.html.twig', array(
                    'entity' => $entity,
                    'form' => $formRessource->createView(),
                    'sections' => $sections,
                    'ressourcesPropo' => $ressourcesProposition,
                    'ressourcesBesoin' => $ressourcesBesoin
        ));
    }

    public function editAction(Request $request) {

        $id = $request->attributes->get('id');
           
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeolocationAdminBundle:Ressources')
                ->findOneBy(array('id' => $id));
       
        $besoin=$entity->getBesoin();
        
        //création du formulaire d'ajout de ressources
        $formRessource = $this->createForm(new RessourcesType(), $entity);
        $formRessource->remove('cpf');
        $formRessource->handleRequest($request);
        
        
        //vérification du formulaire envoyé
        if ($formRessource->isValid()) {
            $user = $this->getUser();
             //vérification que la ressources n'est pas déjà ajoutée pour cette entreprise
                $existeRessource= $em->getRepository('GeolocationAdminBundle:Ressources')
                        ->findOneBy(array(
                            'user'=>$user,
                            'cpf'=>$entity->getCpf(),
                            'adresse_id'=>$entity->getAdresseId()->getId(),
                            'besoin'=>$besoin
                        ));
                
            if(intval($besoin) != $entity->getBesoin() && count($existeRessource)>0){
                     $this->addFlash('danger', 'ressources.flash.already_exist');
            }else{                
                $entity->setUser($user);
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'ressources.flash.update.success');
            }
        } else if (!$formRessource->isValid() && $formRessource->isSubmitted()) {
            $this->addFlash('danger', 'ressources.flash.update.fail');
        }



        return $this->render('GeolocationUserBundle:Ressources:edit.html.twig', array(
                    'entity' => $entity,
                    'form' => $formRessource->createView(),
        ));
    }

    public function deleteAction(Request $request) {
        $id = $request->attributes->get('id');
        $em = $this->getDoctrine()->getManager();
        /** @var Ressources $ressource */
        $ressource = $em->getRepository('GeolocationAdminBundle:Ressources')
                ->find($id);
        
        //vérifie si on est sur un site de production
        if ($request->query->get('page')) {
            $idAdresseForRedirection = $ressource->getAdresseId()->getId();
        }
        $em->remove($ressource);
        $em->flush();
        if ($request->query->get('page')) {
            return $this->redirectToRoute('user_edit_site', array('id' => $idAdresseForRedirection));
        } else {
            return $this->redirectToRoute('user_ressources');
        }
    }

}
