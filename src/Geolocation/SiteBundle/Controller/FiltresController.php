<?php
/**
 * Created by PhpStorm.
 * User: maxencebeno
 * Date: 04/04/2016
 * Time: 10:07
 */

namespace Geolocation\SiteBundle\Controller;

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
            $filterByNomEntreprise = $this->get('site_bundle.filter_by_nom_entreprise');
            $datas = $filterByNomEntreprise->filterByNomEntreprise([], $request);
        }

        $filterByCpf = $this->get('site_bundle.filter_by_cpf');

        $datas = $filterByCpf->filterByCpf($datas, $request);

        if ($request->request->get('city') !== "") {
            $filterByCity = $this->get('site_bundle.filter_by_city');
            $datas = $filterByCity->filterByCity($datas, $request);
        }

        var_dump(count($datas));
        die();
        if ($request->request->get('cp') !== "") {
            $filterByCodePostal = $this->get('site_bundle.filter_by_code_postal');
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