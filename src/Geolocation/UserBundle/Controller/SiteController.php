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

class SiteController extends Controller {

    /**
     * Show the user site and permit to edit it
     */
    public function editAction(Request $request) {
        /** @var User $user */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof User) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $userId = $user->getId();
        $em = $this->getDoctrine()->getManager();
        $sites = $em->getRepository('GeolocationAdminBundle:Adresse')
                ->findBy(array('userId' => $userId));
        //$iso = new Iso(); 
        $entity = new Adresse();
        $form = $this->createForm(new AdresseType(), $entity);
        $form->add('iso', EntityType::class, ['label' => 'ISO', 'class' => 'Geolocation\AdminBundle\Entity\Iso', 'multiple' => true, 'expanded' => true]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            // On cherche ici la latitude et longitude de l'adresse de l'entreprise pour l'afficher correctement sur la google map
            $response = ApiLib::searchAdresse($user);

            if ($response == false) {

                $this->addFlash('danger', "Votre adresse n'a pas pu être trouvée, merci de réessayer.");
                return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
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
                        var_dump($request->request->get('geolocation_adminbundle_adresse'));
                        die;
                        $em->persist($entity);
                        $em->flush();
                        $this->addFlash('success', 'site.flash.create.success');

                        return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
                                    'sites' => $sites,
                                    'form' => $form->createView()
                        ));
                    } else {
                        $this->addFlash('danger', "Votre code postal est erroné, merci de le corriger.");

                        return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
                                    'sites' => $sites,
                                    'form' => $form->createView()
                        ));
                    }
                } else {
                    $this->addFlash('danger', "Votre adresse n'a pas pu être trouvée, merci de réessayer.");

                    return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
                                'sites' => $sites,
                                'form' => $form->createView()
                    ));
                }
            }
        }
        return $this->render('GeolocationUserBundle:Site:edit.html.twig', array(
                    'sites' => $sites,
                    'form' => $form->createView()
        ));
    }

}
