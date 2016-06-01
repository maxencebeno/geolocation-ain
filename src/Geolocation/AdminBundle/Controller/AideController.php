<?php

namespace Geolocation\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;

use Geolocation\AdminBundle\Entity\Pilier;
use Geolocation\AdminBundle\Form\PilierType;
use Geolocation\AdminBundle\Form\PilierFilterType;
use Geolocation\AdminBundle\Repository\PilierRepository;


class AideController extends Controller
{

    public function indexAction()
    {
        return $this->render('GeolocationAdminBundle:Aide:index.html.twig', array());
    }

}
