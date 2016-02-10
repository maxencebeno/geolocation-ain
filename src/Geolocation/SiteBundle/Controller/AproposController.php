<?php

namespace Geolocation\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AproposController extends Controller
{
    public function aproposAction()
    {
        return $this->render('SiteBundle:Apropos:apropos.html.twig');
    }
}
