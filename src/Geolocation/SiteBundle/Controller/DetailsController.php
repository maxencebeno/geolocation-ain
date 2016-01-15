<?php

namespace Geolocation\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DetailsController extends Controller
{
    public function detailsAction($id)
    {
    	$em = $this->getDoctrine()->getManager()->getRepository('GeolocationAdminBundle:User');

    	$user = $em->findOneBy(array('id'=>$id));

        return $this->render('SiteBundle:Details:details.html.twig', array('user'=>$user));
    }
}
