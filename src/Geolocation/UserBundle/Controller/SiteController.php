<?php

namespace Geolocation\UserBundle\Controller;

use Geolocation\AdminBundle\Entity\User;
use Geolocation\AdminBundle\Form\IsoType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Geolocation\AdminBundle\Form\AdresseType;
use Geolocation\AdminBundle\Entity\Adresse;
use Symfony\Component\HttpFoundation\Request;
use Geolocation\AdminBundle\Domain\Api\ApiLib;
use Geolocation\AdminBundle\Entity\Ressources;
use Geolocation\AdminBundle\Form\RessourcesType;

class SiteController extends Controller {

    /**
     * Show the user site and permit to edit it
     */
    public function showAction(Request $request) {
        /** @var User $user */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof User) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $userId = $user->getId();
        $em = $this->getDoctrine()->getManager();
        $sites = $em->getRepository('GeolocationAdminBundle:Adresse')
                ->findBy(array('userId' => $userId));

        $entity = new Adresse();
        $form = $this->createForm(new AdresseType(), $entity);
        $form->add('iso', EntityType::class, ['label' => 'ISO', 'class' => 'Geolocation\AdminBundle\Entity\Iso', 'multiple' => true, 'expanded' => true]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            // On cherche ici la latitude et longitude de l'adresse de l'entreprise pour l'afficher correctement sur la google map
            $response = ApiLib::searchAdresse($user);

            if ($response == false) {

                $this->addFlash('danger', "Votre adresse n'a pas pu être trouvée, merci de réessayer.");
                return $this->render('GeolocationUserBundle:Site:show.html.twig', array(
                            'sites' => $sites,
                            'form' => $form->createView()
                ));
            } else {
                // Geocode your request
                $datas = $response->all();
                if (count($datas) >= 1) {

                    $verifcp = ApiLib::verifCp(strip_tags($entity->getCodePostal()));

                    if ($verifcp === true) {

                        $data = $datas[0];
                        $latitude = $data->getLatitude();
                        $longitude = $data->getLongitude();

                        $entity->setLatitude($latitude);
                        $entity->setLongitude($longitude);
                        $entity->setUserId($userId);

                        $adr = $request->request->get('geolocation_adminbundle_adresse');

                        $entity->setAdresse($adr['adresse']);
                        $entity->setVille($adr['ville']);
                        $entity->setCodePostal($adr['codePostal']);
                        $entity->setTel($adr['tel']);


                        $em->persist($entity);
                        $em->flush();
                        if (array_key_exists('iso', $adr)) {
                            foreach ($adr['iso'] as $i) {
                                echo $i[0];
                                $siteId = $em->getRepository('GeolocationAdminBundle:Adresse')
                                        ->findOneBy(array('adresse' => $adr['adresse']));
                                $siteIso = new \Geolocation\AdminBundle\Entity\SiteIso();

                                $siteIso->setIsoId($i[0]);
                                $siteIso->setSiteId($siteId->getId());

                                $em->persist($siteIso);
                            }
                            $em->flush();
                        }
                        $this->addFlash('success', 'site.flash.create.success');

                        return $this->render('GeolocationUserBundle:Site:show.html.twig', array(
                                    'sites' => $sites,
                                    'form' => $form->createView()
                        ));
                    } else {
                        $this->addFlash('danger', "Votre code postal est erroné, merci de le corriger.");

                        return $this->render('GeolocationUserBundle:Site:show.html.twig', array(
                                    'sites' => $sites,
                                    'form' => $form->createView()
                        ));
                    }
                } else {
                    $this->addFlash('danger', "Votre adresse n'a pas pu être trouvée, merci de réessayer.");

                    return $this->render('GeolocationUserBundle:Site:show.html.twig', array(
                                'sites' => $sites,
                                'form' => $form->createView()
                    ));
                }
            }
        }
        return $this->render('GeolocationUserBundle:Site:show.html.twig', array(
                    'sites' => $sites,
                    'form' => $form->createView()
        ));
    }

    /**
     * Permit to the user to edit one site
     */
    public function editAction(Request $request) {
        $id = $request->attributes->get('id');

        /** @var User $user */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof User) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }


        $userId = $user->getId();
        $em = $this->getDoctrine()->getManager();


        $entity = $em->getRepository('GeolocationAdminBundle:Adresse')
                ->findOneBy(array('id' => $id));

        if ($entity->getUserId() != $user->getId()) {

            $this->addFlash('danger', 'site.flash.edit.bad_site');
            return $this->redirectToRoute('user_show_site');
        }

        $form = $this->createForm(new AdresseType(), $entity);
        $form->add('iso', EntityType::class, ['label' => 'ISO', 'class' => 'Geolocation\AdminBundle\Entity\Iso', 'multiple' => true, 'expanded' => true]);

        $form->handleRequest($request);

        //récupération des ressources
        $sections = $em->getRepository('GeolocationAdminBundle:Section')
                ->findAll();
        $ressource = new Ressources();

        $formRessource = $this->createForm(new RessourcesType(), $ressource);
        $formRessource->handleRequest($request);

        $ressources = $em->getRepository('GeolocationAdminBundle:Ressources')
                ->findBy(array('adresse_id' => $id));
        
     

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
                $ressource->setCpf($cpf);
                $ressource->setUser($user);
                $ressource->setAdresseId($id);

                $em->persist($ressource);
                $em->flush();

                $ressources = $em->getRepository('GeolocationAdminBundle:Ressources')
                        ->findBy(array('adresse_id' => $id));

                $this->addFlash('success', 'ressources.flash.create.success');
            } else {
                $this->addFlash('danger', 'ressources.flash.create.fail');
            }
           return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
                            'adresse' => array(
                                'adresseId' => $id,
                                'lieu' => $entity->getAdresse()
                            ),
                            'form1' => $form->createView(),
                            'form' => $formRessource->createView(),
                            'sections' => $sections,
                            'ressources' => $ressources,
                ));
        }

        if ($form->isValid()) {
            // On cherche ici la latitude et longitude de l'adresse de l'entreprise pour l'afficher correctement sur la google map
            $response = ApiLib::searchAdresse($user);

            if ($response == false) {

                $this->addFlash('danger', "Votre adresse n'a pas pu être trouvée, merci de réessayer.");
                return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
                            'adresse' => array(
                                'adresseId' => $id,
                                'lieu' => $entity->getAdresse()
                            ),
                            'form1' => $form->createView(),
                            'form' => $formRessource->createView(),
                            'sections' => $sections,
                            'ressources' => $ressources,
                ));
            } else {
                // Geocode your request
                $datas = $response->all();
                if (count($datas) >= 1) {

                    $verifcp = ApiLib::verifCp(strip_tags($entity->getCodePostal()));

                    if ($verifcp === true) {

                        $data = $datas[0];
                        $latitude = $data->getLatitude();
                        $longitude = $data->getLongitude();

                        $entity->setLatitude($latitude);
                        $entity->setLongitude($longitude);
                        $entity->setUserId($userId);

                        $adr = $request->request->get('geolocation_adminbundle_adresse');

                        $entity->setAdresse($adr['adresse']);
                        $entity->setVille($adr['ville']);
                        $entity->setCodePostal($adr['codePostal']);
                        $entity->setTel($adr['tel']);


                        $em->persist($entity);
                        $em->flush();

                        if (array_key_exists('iso', $adr)) {

                            foreach ($adr['iso'] as $i) {

                                $siteId = $em->getRepository('GeolocationAdminBundle:Adresse')
                                        ->findOneBy(array('adresse' => $adr['adresse'], 'codePostal' => $adr['codePostal']));
                                $siteIso = new \Geolocation\AdminBundle\Entity\SiteIso();

                                $siteIso->setIsoId($i[0]);
                                $siteIso->setSiteId($siteId->getId());

                                $em->persist($siteIso);
                                $em->flush();
                            }
                        }

                        $this->addFlash('success', 'site.flash.edit.success');
                        return $this->redirectToRoute('user_show_site');
                    } else {
                        $this->addFlash('danger', "Votre code postal est erroné, merci de le corriger.");

                        return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
                                    'adresse' => array(
                                        'adresseId' => $id,
                                        'lieu' => $entity->getAdresse()
                                    ),
                                    'form1' => $form->createView(),
                                    'form' => $formRessource->createView(),
                                    'sections' => $sections,
                                    'ressources' => $ressources,
                        ));
                    }
                } else {
                    $this->addFlash('danger', "Votre adresse n'a pas pu être trouvée, merci de réessayer.");

                    return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
                                'adresse' => array(
                                    'adresseId' => $id,
                                    'lieu' => $entity->getAdresse()
                                ),
                                'form1' => $form->createView(),
                                'form' => $formRessource->createView(),
                                'sections' => $sections,
                                'ressources' => $ressources,
                    ));
                }
            }
        }
        return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
                    'adresse' => array(
                        'adresseId' => $id,
                        'lieu' => $entity->getAdresse()
                    ),
                    'form1' => $form->createView(),
                    'form' => $formRessource->createView(),
                    'sections' => $sections,
                    'ressources' => $ressources,
        ));
    }

    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $site = $em->getRepository('GeolocationAdminBundle:Adresse')
                ->find($id);

        //suppression des ressources
        $ressources = $em->getRepository('GeolocationAdminBundle:Ressources')
                ->findBy(array('adresse_id' => $id));

        foreach ($ressources as $r) {
            $em->remove($r);
        }

        //suppression des iso
        $iso = $em->getRepository('GeolocationAdminBundle:SiteIso')
                ->findBy(array('site_id' => $id));

        foreach ($iso as $i) {
            $em->remove($i);
        }

        //suppression de du site
        $em->remove($site);
        $em->flush();

        return $this->redirectToRoute('user_show_site');
    }

}
