<?php

namespace Geolocation\UserBundle\Controller;

use Geolocation\AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Geolocation\AdminBundle\Form\RessourcesType;
use Geolocation\AdminBundle\Entity\Ressources;
use Geolocation\AdminBundle\Domain\Api\ApiLib;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfileController extends Controller {

    /**
     * Show the user
     */
    public function showAction() {
        /** @var User $user */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $userId = $user->getId();
        $em = $this->getDoctrine()->getManager();
        $ressources = $em->getRepository('GeolocationAdminBundle:Ressources')
                ->findBy(array('user' => $userId));

        /** @var \DateTime $date */
        if ($user->getDateCreationEntreprise() !== null) {
            $date = $user->getDateCreationEntreprise();
            $user->setDateCreationEntreprise($date->format('d/m/Y'));
        }

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
                    'user' => $user,
                    'ressources' => $ressources,
        ));
    }

    /**
     * Edit the user
     */
    public function editAction(Request $request) {
        /** @var User $user */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $dateCreationEntrepriseString = $request->request->get('fos_user_profile_form')['dateCreationEntreprise'];
            $dateCreationEntreprise = ApiLib::dateToMySQL($dateCreationEntrepriseString);

            $user->setDateCreationEntreprise($dateCreationEntreprise);
            // var_dump($date);
            //die;
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            if ($request->files->get('fos_user_profile_form')['fileKbis'] !== NULL) {
                $this->uploadKbis($request->files->get('fos_user_profile_form')['fileKbis'], $user->getUsername());
            }

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_show');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }


        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    protected function getUploadRootDir() {
        // le chemin absolu du répertoire dans lequel sauvegarder les photos de profil
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/kbis/';
    }

    public function uploadKbis(UploadedFile $file, $username) {
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
    
    public function downloadFileAction(Request $request)
    {
        // On récupère l'utilisateur
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('GeolocationAdminBundle:User')
            ->findOneBy(array(
                'id' => $request->attributes->get('id')
            ));

        $path = $this->get('kernel')->getRootDir() . "/../web/uploads/kbis/" . $user->getKbis();
        $content = file_get_contents($path);

        $response = new Response();

        $response->headers->set('Content-Type', 'text/pdf');
        
        $response->setContent($content);
        return $response;
    }

}
