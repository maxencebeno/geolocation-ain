<?php

namespace Geolocation\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GeolocationAdminBundle:Default:index.html.twig');
    }
}
