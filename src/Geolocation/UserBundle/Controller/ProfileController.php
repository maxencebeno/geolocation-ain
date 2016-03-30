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
use Geolocation\AdminBundle\Form\RessourcesType;
use Geolocation\AdminBundle\Entity\Ressources;
use Geolocation\AdminBundle\Domain\Api\ApiLib;

class ProfileController extends Controller {

    /**
     * Show the user
     */
    public function showAction() {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $userId = $user->getId();
        $em = $this->getDoctrine()->getManager();
        $ressources = $em->getRepository('GeolocationAdminBundle:Ressources')
                ->findBy(array('user' => $userId));
        
        /** @var \DateTime $date */
        $date = $user->getDateCreationEntreprise();
        $user->setDateCreationEntreprise($date->format('d/m/Y'));

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
                    'user' => $user,
                    'ressources' => $ressources,
        ));
    }

    /**
     * Edit the user
     */
    public function editAction(Request $request) {
        /** @var Geolocation\AdminBundle\Entity\User $user */
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

}
