<?php

namespace Geolocation\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PartenairesController extends Controller
{
    public function partenairesAction()
    {
        return $this->render('SiteBundle:Partenaires:partenaires.html.twig');
    }
}
