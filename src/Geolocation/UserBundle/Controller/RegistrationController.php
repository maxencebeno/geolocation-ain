<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geolocation\UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Geolocation\AdminBundle\Domain\Api\ApiLib;
use Geolocation\AdminBundle\Entity\Adresse;
use Geolocation\AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

/**
 * Controller managing the registration
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends BaseController
{

    public function preRegisterAction(Request $request)
    {
        return $this->render('GeolocationUserBundle:Registration:pre_register.html.twig', array());
    }

    public function registerAction(Request $request)
    {
        $errors = [];
        $em = $this->getDoctrine()->getManager();
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        /** @var User $user */
        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        switch ($request->attributes->get('type')) {
            case 'entreprise':
                $form->remove('rna');
                break;
            case 'association':
                $form->remove('fileKbis');
                $form->remove('siren');
        }

        $form->handleRequest($request);

        if ($form->isValid()) {

            $dateCreationEntrepriseString = $request->request->get('fos_user_registration_form')['dateCreationEntreprise'];
            $dateCreationEntreprise = ApiLib::dateToMySQL($dateCreationEntrepriseString);

            $user->setDateCreationEntreprise($dateCreationEntreprise);

            // On cherche ici la latitude et longitude de l'adresse de l'entreprise pour l'afficher correctement sur la google map

            $response = ApiLib::searchAdresse($user);

            if ($response === false) {
                $errors[] = "Votre adresse n'a pas pu être trouvée, merci de réessayer.";

                return $this->render('FOSUserBundle:Registration:register.html.twig', array(
                    'form' => $form->createView(),
                    'type' => $request->attributes->get('type'),
                    'errors' => $errors
                ));
            } else {
                // Geocode your request
                $datas = $response->all();
                if (count($datas) >= 1) {

                    $verifcp = ApiLib::verifCp(strip_tags($user->getCodePostal()));

                    if ($verifcp === true) {

                        $data = $datas[0];
                        $latitude = $data->getLatitude();
                        $longitude = $data->getLongitude();

                        $user->setLatitude($latitude);
                        $user->setLongitude($longitude);

                        $em->persist($user);
                        
                        $adresse = new Adresse();
                        
                        $adresse->setAdresse($user->getAdresse());
                        $adresse->setCodePostal($user->getCodePostal());
                        $adresse->setLatitude($latitude);
                        $adresse->setLongitude($longitude);
                        $adresse->setTel($user->getTel());
                        $adresse->setIsPublic(true);
                        $adresse->setUser($user);
                        $adresse->setVille($user->getVille());
                        $adresse->setMain(true);
                        $adresse->setPilier($user->getPilier());

                        $em->persist($adresse);

                        $em->flush();

                    } else {
                        $errors[] = "Votre code postal est erroné, merci de le corriger.";

                        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
                            'form' => $form->createView(),
                            'type' => $request->attributes->get('type'),
                            'errors' => $errors
                        ));
                    }
                } else {
                    $errors[] = "Votre adresse n'a pas pu être trouvée, merci de réessayer.";

                    return $this->render('FOSUserBundle:Registration:register.html.twig', array(
                        'form' => $form->createView(),
                        'type' => $request->attributes->get('type'),
                        'errors' => $errors
                    ));
                }
            }

            $event = new FormEvent($form, $request);
            //$dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            /** @var User $user */
            $user->setEnabled(false);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_registration_confirmed');
                $response = new RedirectResponse($url);
            }

            //$dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            if ($request->files->get('fos_user_registration_form')['fileKbis'] !== NULL) {
                $this->uploadKbis($request->files->get('fos_user_registration_form')['fileKbis'], $user->getUsername());
            }

            $event = new FormEvent($form, $request);
            //$dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            $this->addFlash('info', 'Merci pour votre inscription, votre demande sera étudiée dans les plus brefs délais');

            $url = $this->generateUrl('site_homepage');

            $response = new RedirectResponse($url);

            /*if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_registration_confirmed');
                $response = new RedirectResponse($url);
            }*/

            //$dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
            'type' => $request->attributes->get('type'),
            'errors' => $errors
        ));
    }

    /**
     * Tell the user to check his email provider
     */
    public function checkEmailAction()
    {
        $email = $this->get('session')->get('fos_user_send_confirmation_email/email');
        $this->get('session')->remove('fos_user_send_confirmation_email/email');
        $user = $this->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user) {
            return $this->redirect($this->generateUrl('site'));
        }

        return $this->render('FOSUserBundle:Registration:checkEmail.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Receive the confirmation token from user email provider, login the user
     */
    public function confirmAction(Request $request, $token)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('fos_user_registration_confirmed');
            $response = new RedirectResponse($url);
        }

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }

    /**
     * Tell the user his account is now confirmed
     */
    public function confirmedAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('FOSUserBundle:Registration:confirmed.html.twig', array(
            'user' => $user,
        ));
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire dans lequel sauvegarder les photos de profil
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/kbis/';
    }

    public function uploadKbis(UploadedFile $file, $username)
    {
        // Nous utilisons le nom de fichier original, donc il est dans la pratique 
        // nécessaire de le nettoyer xpour éviter les problèmes de sécurité
        // move copie le fichier présent chez le client dans le répertoire indiqué.
        $em = $this->getDoctrine()->getManager();

        $userBDD = $em->getRepository('GeolocationAdminBundle:User')
            ->findOneBy(array(
                'username' => $username
            ));

        if ($userBDD->getKbis() !== null) {
            @unlink(__DIR__ . '/../../../../web/uploads/kbis/' . $userBDD->getKbis());
        }

        preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move($this->getUploadRootDir(), $file->getClientOriginalName());

        // On sauvegarde le nom de fichier
        $kbis = $file->getClientOriginalName();

        $userBDD->setKbis($kbis);

        $em->persist($userBDD);
        $em->flush();
    }

}
