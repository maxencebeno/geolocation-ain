<?php
/**
 * Created by PhpStorm.
 * User: maxencebeno
 * Date: 01/02/2016
 * Time: 16:55
 */

namespace Geolocation\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class AjaxController extends Controller
{
    public function getDivisionAction(Request $request) {
        $sectionId = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        $cpfs = $em->getRepository('GeolocationAdminBundle:Cpf')
            ->findBy(array(
                'section' => $sectionId
            ));

        $sections = [];

        foreach ($cpfs as $cpf) {
            $sections[] = $cpf->getSection();
        }

        $normalizer = new GetSetMethodNormalizer();
        $serializer = new Serializer(array($normalizer), array('albums' => new JsonEncoder()));
        $jsonContent = $serializer->serialize($sections, 'json');
        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}