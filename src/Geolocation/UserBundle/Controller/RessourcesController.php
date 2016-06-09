<?php

namespace Geolocation\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Geolocation\AdminBundle\Entity\Ressources;
use Geolocation\AdminBundle\Form\RessourcesType;

class RessourcesController extends Controller
{

    public function showAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        /*$sections = $em->getRepository('GeolocationAdminBundle:Section')
                ->findBy(['affiche' => true]);*/
        $entity = new Ressources();

        //création du formulaire d'ajout de ressources
        $formRessource = $this->createForm(new RessourcesType(), $entity);
        $formRessource->handleRequest($request);

        $userId = $this->getUser()->getId();

        //récupération des ressources (besoin et proposition) de l'entreprise courant pour pouvoir les afficher par la suite
        $ressourcesProposition = $em->getRepository('GeolocationAdminBundle:Ressources')
            ->findBy(array('user' => $userId, 'site' => NULL, 'besoin' => false));
        $ressourcesBesoin = $em->getRepository('GeolocationAdminBundle:Ressources')
            ->findBy(array('user' => $userId, 'site' => NULL, 'besoin' => true));


        //vérification du formulaire envoyé
        if ($formRessource->isValid()) {
            $codeNaf = $request->request->get('codeNaf');

            $user = $this->getUser();

            /*$section = $em->getRepository('GeolocationAdminBundle:Section')
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
              )); */

            $cpf = $em->getRepository('GeolocationAdminBundle:Cpf')
                ->findOneBy(array(
                    'id' => $codeNaf
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
                $existeRessource = $em->getRepository('GeolocationAdminBundle:Ressources')
                    ->findOneBy(array(
                        'user' => $user,
                        'cpf' => $cpf,
                        'site' => null,
                        'besoin' => $entity->getBesoin()
                    ));

                if ($existeRessource !== null) {
                    $this->addFlash('danger', $this->get('translator')->trans('ressources.flash.already_exist', [], 'ressources'));
                } else {
                    //ajout en base    
                    $entity->setCpf($cpf);
                    $entity->setUser($user);
                    $em->persist($entity);
                    $em->flush();
                    $this->addFlash('success', $this->get('translator')->trans('ressources.flash.create.success', [], 'ressources'));

                    $ressourcesProposition = $em->getRepository('GeolocationAdminBundle:Ressources')
                        ->findBy(array('user' => $userId, 'site' => NULL, 'besoin' => false));
                    $ressourcesBesoin = $em->getRepository('GeolocationAdminBundle:Ressources')
                        ->findBy(array('user' => $userId, 'site' => NULL, 'besoin' => true));
                }
            } else {
                $this->addFlash('danger', $this->get('translator')->trans('ressources.flash.create.fail', [], 'ressources'));
            }

            return $this->render('GeolocationUserBundle:Ressources:show.html.twig', array(
                'entity' => $entity,
                'form' => $formRessource->createView(),
                //'sections' => $sections,
                'ressourcesPropo' => $ressourcesProposition,
                'ressourcesBesoin' => $ressourcesBesoin
            ));
        }


        return $this->render('GeolocationUserBundle:Ressources:show.html.twig', array(
            'entity' => $entity,
            'form' => $formRessource->createView(),
            //'sections' => $sections,
            'ressourcesPropo' => $ressourcesProposition,
            'ressourcesBesoin' => $ressourcesBesoin
        ));
    }

    public function editAction(Request $request)
    {

        $id = $request->attributes->get('id');

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        // Récupération des ressources de l'entreprise
        $entity = $em->getRepository('GeolocationAdminBundle:Ressources')
            ->findOneBy(array('id' => $id));

        if ($entity->getUser()->getId() != $user->getId()) {

            $this->addFlash('danger', $this->get('translator')->trans('ressources.flash.edit.bad_ressource', [], 'ressources'));
            return $this->redirectToRoute('user_show_site');
        }

        $besoin = $entity->getBesoin();

        //création du formulaire d'ajout de ressources
        $formRessource = $this->createForm(new RessourcesType(), $entity);
        $formRessource->remove('cpf');
        $formRessource->handleRequest($request);


        //vérification du formulaire envoyé
        if ($formRessource->isValid()) {

            //vérification que la ressources n'est pas déjà ajoutée pour cette entreprise
            $existeRessource = $em->getRepository('GeolocationAdminBundle:Ressources')
                ->findOneBy(array(
                    'user' => $user,
                    'cpf' => $entity->getCpf(),
                    'site' => ($entity->getSite() != null ? $entity->getSite()->getId() : null),
                    'besoin' => $besoin
                ));

            if (intval($besoin) === $entity->getBesoin() && $existeRessource !== null) {
                $this->addFlash('danger', $this->get('translator')->trans('ressources.flash.already_exist', [], 'ressources'));
            } else {
                $entity->setUser($user);
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', $this->get('translator')->trans('ressources.flash.update.success', [], 'ressources'));
            }
        } else if (!$formRessource->isValid() && $formRessource->isSubmitted()) {
            $this->addFlash('danger', $this->get('translator')->trans('ressources.flash.update.fail', [], 'ressources'));
        }


        return $this->render('GeolocationUserBundle:Ressources:edit.html.twig', array(
            'entity' => $entity,
            'form' => $formRessource->createView(),
        ));
    }

    public function deleteAction(Request $request)
    {
        $id = $request->attributes->get('id');

        $em = $this->getDoctrine()->getManager();
        /** @var Ressources $ressource */
        $ressource = $em->getRepository('GeolocationAdminBundle:Ressources')
            ->find($id);
        $userId = $this->getUser()->getId();
        //vérification que l'utilisateur supprime une ressource liée a son compte
        if ($ressource->getUser()->getId() != $userId) {
            $this->addFlash('danger', $this->get('translator')->trans('ressources.flash.edit.bad_ressource', [], 'ressources'));
            return $this->redirectToRoute('user_show_site');
        }

        //vérifie si on est sur un site de production
        if ($request->query->get('page')) {
            $idAdresseForRedirection = $ressource->getSite()->getId();
        } else {
            $idAdresseForRedirection = null;
        }
        $em->remove($ressource);
        $em->flush();
        $this->addFlash('success', $this->get('translator')->trans('ressources.flash.delete.success', [], 'ressources'));
        if ($request->query->get('page')) {
            return $this->redirectToRoute('user_edit_site', array('id' => $idAdresseForRedirection));
        } else {
            return $this->redirectToRoute('user_ressources');
        }
    }

}
