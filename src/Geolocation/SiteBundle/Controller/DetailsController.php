<?php

namespace Geolocation\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DetailsController extends Controller
{
    public function detailsAction($id)
    {
    	$em = $this->getDoctrine()->getManager()->getRepository('GeolocationAdminBundle:User');
        $em2 = $this->getDoctrine()->getManager()->getRepository('GeolocationAdminBundle:Ressources');

    	$user = $em->findOneBy(array('id'=>$id));
        $ressources = $em2->findBy(array('user'=>$user->getId()));
        
        $cpfs = [];
        
        foreach ($ressources as $ressource) {
            $cpfs[] = $ressource->getCpf();
        }

        return $this->render('SiteBundle:Details:details.html.twig', array('user'=>$user, 'ress'=>$ressources));
    }
}
