<?php

namespace Geolocation\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\Translator;
use Symfony\Component\HttpFoundation\Request;

class ListeInscritsController extends Controller
{

    public function listeInscritsAction(Request $request)
    {
        $translator = new Translator($request->getLocale());
        $em = $this->getDoctrine()->getManager();
        $array = [];

        $piliers = $em->getRepository('GeolocationAdminBundle:Pilier')
            ->findBy([], ['nom' => 'ASC']);

        foreach ($piliers as $pilier) {
            if (!isset($array[$pilier->getId()])) {
                $array[$pilier->getId()] = [];
            }
            $entreprises = $em->getRepository('GeolocationAdminBundle:Adresse')
                ->findBy([
                    'pilier' => $pilier
                ], [
                    'nom' => 'ASC'
                ]);
            if (count($entreprises) > 0) {
                $array[$pilier->getId()] = ['entreprises' => $entreprises, 'pilier' => $pilier->getNom()];
            } else {
                $array[$pilier->getId()] = ['entreprises' => null, 'pilier' => $pilier->getNom()];
            }
        }

        $entreprises = $em->getRepository('GeolocationAdminBundle:Adresse')
            ->findBy(['pilier' => null]);

        $array['nopilier'] = ['entreprises' => $entreprises, 'pilier' => $translator->trans('entreprise.no_pilier', [], 'GeolocationUserBundle')];


        //  var_dump($entreprises);
        return $this->render('SiteBundle:ListeInscrits:listeinscrits.html.twig',
            array('datas' => $array)
        );
    }

}
