<?php

namespace Geolocation\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AideController extends Controller
{
    public function aideAction()
    {
        return $this->render('SiteBundle:Aide:aide.html.twig');
    }
}
