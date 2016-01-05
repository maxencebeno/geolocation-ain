<?php

namespace Geolocation\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProfileController extends Controller {

    /**
     * Show the user
     */
    public function showAction() {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
                    'user' => $user
        ));
    }

    /**
     * Edit the user
     */
    public function editAction(Request $request) {
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
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $userManager->updateUser($user);
            if ($event->getRequest()->files->get('fos_user_profile_form')['file'] !== NULL) {
                $this->uploadProfilePicture($event->getRequest()->files->get('fos_user_profile_form')['file'], $user->getUsername());
            }

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

    protected function getUploadRootDir($username) {
        // le chemin absolu du répertoire dans lequel sauvegarder les photos de profil
        return __DIR__ . '/../../../../web/' . $this->getUploadDir($username);
    }

    protected function getUploadDir($username) {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/user_pictures/' . $username;
    }

    public function uploadProfilePicture(UploadedFile $file, $username) {
        // Nous utilisons le nom de fichier original, donc il est dans la pratique 
        // nécessaire de le nettoyer xpour éviter les problèmes de sécurité
        // move copie le fichier présent chez le client dans le répertoire indiqué.
        if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
            $ffmpeg = "/usr/local/bin/ffmpeg";
        } else {
            $ffmpeg = "/usr/local/Cellar/ffmpeg/2.6.3/bin/ffmpeg";
        }
        $filename = explode('.', $file->getClientOriginalName());
        $filenamePng = trim($filename[0]) . '.png';
        preg_replace('/\s+/', '_', $filenamePng);
        $path = $this->getUploadRootDir($username) . '/' . $file->getClientOriginalName();
        $file->move($this->getUploadRootDir($username), $file->getClientOriginalName());
        shell_exec("$ffmpeg -i $path -vf scale=150:-1 uploads/user_pictures/$username/$filenamePng 2>&1");
        @unlink(__DIR__ . '/../../../../web/uploads/user_pictures/' . $file->getClientOriginalName());
        $em = $this->getDoctrine()->getManager();

        $userBDD = $em->getRepository('MediamotionUserBundle:User')
                ->findOneBy(array(
            'username' => $username
        ));

        if ($userBDD->getPictureName() !== null) {
            @unlink(__DIR__ . '/../../../../web/uploads/user_pictures/' . $username . '/' . $userBDD->getPictureName());
        }

        // On sauvegarde le nom de fichier
        $pictureName = $file->getClientOriginalName();

        $userBDD->setPictureName($pictureName);

        $em->persist($userBDD);
        $em->flush();
    }

    public function displayAction($username) {
        $images = $this->getDoctrine()
                ->getRepository('MediamotionUserBundle:User')
                ->findBy(array(
            'username' => $username
        ));

        $pictures = array();
        foreach ($images as $image) {
            $picture = $image;
            array_push($pictures, $picture);
        }
        $requestedPhoto = $pictures[0];

        $filename = explode('.', $requestedPhoto->getPictureName());


        $dirname = $this->get('kernel')->getRootDir() . '/../web/uploads/user_pictures/' . $username . '/';
        $fichier = $dirname . $filename[0] . '.' . 'png';
        $extension = 'png';

        // Si la musique n'est pas trouvée on arrête tout
        if ($requestedPhoto === null) {
            throw new NotFoundHttpException('The requested file was not found');
        }

        // Création d'une réponse objet
        $response = new Response();
        if (file_exists($fichier) && is_readable($fichier)) {
            // On va chercher le fichier en php objet
            $response->setContent(file_get_contents($fichier));

            // Header en php objet pour que le navigateur sache le type du fichier
            $response->headers->set('Content-Type', 'image/' . $extension);
            // Autres headers en php objet aussi
            //$response->headers->set('Content-disposition', 'inline');
            $response->headers->set('Content-disposition', 'filename=' . $requestedPhoto->getPictureName());
            $response->headers->set('Content-Range', 'bytes 0-' . filesize($fichier)); // Pour la durée du fichier
            $response->headers->set('Expires', '0');
            $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
            $response->headers->set('Pragma', 'public');
            $response->headers->set('X-pad', 'avoid browser bug');
            $response->headers->set('Accept-Ranges', 'bytes'); // Pour la durée du fichier
            $response->headers->set('Content-Length', filesize($fichier));

            return $response;
        } else {
            $dirname = $this->get('kernel')->getRootDir() . '/../web/bundles/mediamotionfm2/images/';
            $fichier = $dirname . 'defaultProfilePic.png';
            $extension = 'png';

            // Création d'une réponse objet
            $response = new Response();
            if (file_exists($fichier) && is_readable($fichier)) {
                // On va chercher le fichier en php objet
                $response->setContent(file_get_contents($fichier));

                // Header en php objet pour que le navigateur sache le type du fichier
                $response->headers->set('Content-Type', 'image/' . $extension);
                // Autres headers en php objet aussi
                //$response->headers->set('Content-disposition', 'inline');
                $response->headers->set('Content-disposition', 'filename=defaultProfilePic.png');
                $response->headers->set('Content-Range', 'bytes 0-' . filesize($fichier)); // Pour la durée du fichier
                $response->headers->set('Expires', '0');
                $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
                $response->headers->set('Pragma', 'public');
                $response->headers->set('X-pad', 'avoid browser bug');
                $response->headers->set('Accept-Ranges', 'bytes'); // Pour la durée du fichier
                $response->headers->set('Content-Length', filesize($fichier));

                return $response;
            } else {
                // On lance une exception (404 not found) proprement avec une méthode Symfony2
                throw new NotFoundHttpException();
            }
        }
    }

}
