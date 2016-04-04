<?php
/**
 * Created by PhpStorm.
 * User: maxencebeno
 * Date: 04/04/2016
 * Time: 10:07
 */

namespace Geolocation\SiteBundle\Controller;

use Geolocation\AdminBundle\Entity\Cpf;
use Geolocation\AdminBundle\Entity\Ressources;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class FiltresController extends Controller
{
    public function getEntreprisesByFiltersAction(Request $request)
    {
        
        if ($request->request->get('entreprise') !== null) {
            $filterByNomEntreprise = $this->get('filter_by_nom_entreprise');
            $datas = $filterByNomEntreprise->filterByNomEntreprise($request);
        }

        $filterByCpf = $this->get('filter_by_cpf');

        $datas = $filterByCpf->filterByCpf($request);

        if ($request->request->get('city') !== null) {
            $filterByCity = $this->get('filter_by_city');
            $datas = $filterByCity->filterByCity($datas, $request);
        }
        if ($request->request->get('cp') !== null) {
            $filterByCodePostal = $this->get('filter_by_code_postal');
            $datas = $filterByCodePostal->filterByCodePostal($datas, $request);
        }

        $ignoredAttributes = array('user');
        $normalizer = new GetSetMethodNormalizer();
        $normalizer->setIgnoredAttributes($ignoredAttributes);
        $serializer = new Serializer(array($normalizer), array('ressources' => new JsonEncoder()));
        $jsonContent = $serializer->serialize($datas, 'json');

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}