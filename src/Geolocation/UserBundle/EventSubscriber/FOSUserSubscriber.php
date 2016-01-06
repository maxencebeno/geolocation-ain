<?php

namespace Geolocation\UserBundle\EventSubscriber;

use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class FOSUserSubscriber implements EventSubscriberInterface {

    protected $router;
    protected $securityContext;

    public function __construct(
    UrlGeneratorInterface $router, SecurityContextInterface $securityContext
    ) {
        $this->router = $router;
        $this->securityContext = $securityContext;
    }

    public static function getSubscribedEvents() {
        return array(
            FOSUserEvents::REGISTRATION_INITIALIZE => 'forwardToRouteIfUser',
        );
    }

    public function forwardToRouteIfUser(GetResponseUserEvent $event) {
        if (!$this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return;
        }
        $url = $this->router->generate('site');

        $response = new RedirectResponse($url);

        $event->setResponse($response);
    }

}
