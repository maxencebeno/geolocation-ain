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

class SiteController extends Controller
{

    /**
     * Show the user site and permit to edit it
     */
    public function showAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof User) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $em = $this->getDoctrine()->getManager();
        $sites = $em->getRepository('GeolocationAdminBundle:Adresse')
            ->findBy(array('user' => $user, 'main' => false));

        $entity = new Adresse();
        $entity->setNom($user->getNom());
        $form = $this->createForm(new AdresseType(), $entity);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $adr = $request->request->get('geolocation_adminbundle_adresse');

            $entity->setAdresse($adr['adresse']);
            $entity->setVille($adr['ville']);
            $entity->setCodePostal($adr['codePostal']);

            // On cherche ici la latitude et longitude de l'adresse de l'entreprise pour l'afficher correctement sur la google map
            $response = ApiLib::searchAdresse(null, $entity);

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
                        $entity->setUser($user);
                        $entity->setTel($adr['tel']);
                        $entity->setMain(false);


                        $em->persist($entity);
                        $em->flush();

                        $siteId = $em->getRepository('GeolocationAdminBundle:Adresse')
                            ->findOneBy(array('adresse' => $adr['adresse'], 'ville' => $adr['ville'], 'codePostal' => $adr['codePostal']));
                        if (array_key_exists('iso', $adr)) {
                            foreach ($adr['iso'] as $i) {
                                $iso = $em->getRepository('GeolocationAdminBundle:Iso')
                                    ->findOneBy(['id' => $i[0]]);

                                $siteIso = new \Geolocation\AdminBundle\Entity\SiteIso();

                                $siteIso->setIsoId($iso);
                                $siteIso->setSiteId($siteId);
                                if ($request->request->get('certifie-' . $i) === "oui") {
                                    $siteIso->setCertifie(true);
                                    $siteIso->setEnCoursCertification(false);
                                    if ($request->request->get('date_certification-' . $i) !== null) {
                                        $siteIso->setDateCertification(ApiLib::dateToMySQL($request->request->get('date_certification-' . $i)));
                                    }
                                } else {
                                    $siteIso->setCertifie(false);
                                    $siteIso->setEnCoursCertification(true);
                                }

                                if ($request->request->get('other')) {
                                   if($i == 5){
                                         $siteIso->setAutre($request->request->get('other'));
                                    }
                                }

                                $em->persist($siteIso);
                            }
                            $em->flush();
                        }
                        $this->addFlash('success', 'site.flash.create.success');

                        return $this->redirectToRoute('user_edit_site', array('id' => $siteId->getId()), 302);
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
    public function editAction(Request $request)
    {
        $id = $request->attributes->get('id');

        /** @var User $user */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof User) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $userId = $user->getId();
        $em = $this->getDoctrine()->getManager();

        /** @var Adresse $entity */
        $entity = $em->getRepository('GeolocationAdminBundle:Adresse')
            ->findOneBy(array('id' => $id));

        $isoAlreadyIn = $em->getRepository('GeolocationAdminBundle:SiteIso')
            ->findBy([
                'siteId' => $entity
            ]);

        if ($entity->getUser()->getId() != $user->getId()) {

            $this->addFlash('danger', 'site.flash.edit.bad_site');
            return $this->redirectToRoute('user_show_site');
        }

        $form = $this->createForm(new AdresseType(), $entity);

        $form->handleRequest($request);

        //récupération des ressources
        $sections = $em->getRepository('GeolocationAdminBundle:Section')
            ->findAll();
        $ressource = new Ressources();

        $formRessource = $this->createForm(new RessourcesType(), $ressource);
        $formRessource->handleRequest($request);

        $ressourcesProposition = $em->getRepository('GeolocationAdminBundle:Ressources')
            ->findBy(array('user' => $userId, 'adresse_id' => $entity, 'besoin' => false));
        $ressourcesBesoin = $em->getRepository('GeolocationAdminBundle:Ressources')
            ->findBy(array('user' => $userId, 'adresse_id' => $entity, 'besoin' => true));

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
                $ressource->setAdresseId($entity);

                $em->persist($ressource);
                $em->flush();

                $ressourcesProposition = $em->getRepository('GeolocationAdminBundle:Ressources')
                    ->findBy(array('user' => $userId, 'adresse_id' => $entity, 'besoin' => false));
                $ressourcesBesoin = $em->getRepository('GeolocationAdminBundle:Ressources')
                    ->findBy(array('user' => $userId, 'adresse_id' => $entity, 'besoin' => true));

                $this->addFlash('success', 'ressources.flash.create.success');
            } else {
                $this->addFlash('danger', 'ressources.flash.create.fail');
            }
            return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
                'adresse' => array(
                    'adresseId' => $id,
                    'lieu' => $entity->getAdresse(),
                    'obj' => $entity
                ),
                'form1' => $form->createView(),
                'form' => $formRessource->createView(),
                'sections' => $sections,
                'ressourcesPropo' => $ressourcesProposition,
                'ressourcesBesoin' => $ressourcesBesoin,
                'isoAlreadyIn' => $isoAlreadyIn
            ));
        }

        if ($form->isValid()) {

            $adr = $request->request->get('geolocation_adminbundle_adresse');

            $entity->setAdresse($adr['adresse']);
            $entity->setVille($adr['ville']);
            $entity->setCodePostal($adr['codePostal']);

            // On cherche ici la latitude et longitude de l'adresse de l'entreprise pour l'afficher correctement sur la google map
            $response = ApiLib::searchAdresse(null, $entity);

            if ($response == false) {

                $this->addFlash('danger', "Votre adresse n'a pas pu être trouvée, merci de réessayer.");
                return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
                    'adresse' => array(
                        'adresseId' => $id,
                        'lieu' => $entity->getAdresse(),
                        'obj' => $entity
                    ),
                    'form1' => $form->createView(),
                    'form' => $formRessource->createView(),
                    'sections' => $sections,
                    'ressourcesPropo' => $ressourcesProposition,
                    'ressourcesBesoin' => $ressourcesBesoin,
                    'isoAlreadyIn' => $isoAlreadyIn
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
                        $entity->setUser($user);
                        $entity->setTel($adr['tel']);


                        $em->persist($entity);
                        $em->flush();

                        $sitesIso = $em->getRepository('GeolocationAdminBundle:SiteIso')
                            ->findBy([
                                'siteId' => $entity,
                            ]);


                        foreach ($sitesIso as $siteIso) {
                            $em->remove($siteIso);
                        }
                        $em->flush();

                        if (array_key_exists('iso', $adr)) {                            
                            foreach ($adr['iso'] as $i) {
                                $iso = $em->getRepository('GeolocationAdminBundle:Iso')
                                    ->findOneBy(['id' => $i[0]]);
                                $siteIso = new \Geolocation\AdminBundle\Entity\SiteIso();
                                $siteIso->setIsoId($iso);
                                $siteIso->setSiteId($entity);
                                if ($request->request->get('certifie-' . $i) === "oui") {
                                    $siteIso->setCertifie(true);
                                    $siteIso->setEnCoursCertification(false);
                                    if ($request->request->get('date_certification-' . $i) !== null) {
                                        $siteIso->setDateCertification(ApiLib::dateToMySQL($request->request->get('date_certification-' . $i)));
                                    }
                                } else {
                                    $siteIso->setCertifie(false);
                                    $siteIso->setEnCoursCertification(true);
                                }                                
                                if ($request->request->get('other')) {
                                    if($i == 5){
                                         $siteIso->setAutre($request->request->get('other'));
                                    }
                                }
                                $em->persist($siteIso);
                            }
                            $em->flush();
                        }

                        $this->addFlash('success', 'site.flash.edit.success');

                        return $this->redirectToRoute('user_show_site');
                    } else {
                        $this->addFlash('danger', "Votre code postal est erroné, merci de le corriger.");

                        return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
                            'adresse' => array(
                                'adresseId' => $id,
                                'lieu' => $entity->getAdresse(),
                                'obj' => $entity
                            ),
                            'form1' => $form->createView(),
                            'form' => $formRessource->createView(),
                            'sections' => $sections,
                            'ressourcesPropo' => $ressourcesProposition,
                            'ressourcesBesoin' => $ressourcesBesoin,
                            'isoAlreadyIn' => $isoAlreadyIn
                        ));
                    }
                } else {
                    $this->addFlash('danger', "Votre adresse n'a pas pu être trouvée, merci de réessayer.");

                    return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
                        'adresse' => array(
                            'adresseId' => $id,
                            'lieu' => $entity->getAdresse(),
                            'obj' => $entity
                        ),
                        'form1' => $form->createView(),
                        'form' => $formRessource->createView(),
                        'sections' => $sections,
                        'ressourcesPropo' => $ressourcesProposition,
                        'ressourcesBesoin' => $ressourcesBesoin,
                        'isoAlreadyIn' => $isoAlreadyIn
                    ));
                }
            }
        }
        return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
            'adresse' => array(
                'adresseId' => $id,
                'lieu' => $entity->getAdresse(),
                'obj' => $entity
            ),
            'form1' => $form->createView(),
            'form' => $formRessource->createView(),
            'sections' => $sections,
            'ressourcesPropo' => $ressourcesProposition,
            'ressourcesBesoin' => $ressourcesBesoin,
            'isoAlreadyIn' => $isoAlreadyIn
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $site = $em->getRepository('GeolocationAdminBundle:Adresse')
            ->find($id);

        //suppression des ressources
        $ressources = $em->getRepository('GeolocationAdminBundle:Ressources')
            ->findBy(array('adresse_id' => $id));

        if ($ressources !== null) {
            foreach ($ressources as $r) {
                $em->remove($r);
            }
        }

        //suppression des iso
        $iso = $em->getRepository('GeolocationAdminBundle:SiteIso')
            ->findBy(array('siteId' => $site));

        if ($iso !== null) {
            foreach ($iso as $i) {
                $em->remove($i);
            }
        }
        //suppression de du site
        $em->remove($site);
        $em->flush();

        $this->addFlash('success', "Le site a été supprimé avec succès");
        return $this->redirectToRoute('user_show_site');
    }

}
