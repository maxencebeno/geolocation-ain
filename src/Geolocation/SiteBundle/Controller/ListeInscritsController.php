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

        // Récupération des piliers par ordre alphabétique
        $piliers = $em->getRepository('GeolocationAdminBundle:Pilier')
            ->findBy([], ['nom' => 'ASC']);

        
        // Pour chaque piliers
        foreach ($piliers as $pilier) {
            if (!isset($array[$pilier->getId()])) {
                $array[$pilier->getId()] = [];
            }
            
            // Récupération des sites par rapport au pilier, par ordre alphabétique
            $entreprises = $em->getRepository('GeolocationAdminBundle:Site')
                ->findBy([
                    'pilier' => $pilier
                ], [
                    'nom' => 'ASC'
                ]);
            
            // Si on en trouve, on remplit le tableau sinon on le laisse vide
            if (count($entreprises) > 0) {
                $array[$pilier->getId()] = ['entreprises' => $entreprises, 'pilier' => $pilier->getNom()];
            } else {
                $array[$pilier->getId()] = ['entreprises' => null, 'pilier' => $pilier->getNom()];
            }
        }

        $entreprises = $em->getRepository('GeolocationAdminBundle:Site')
            ->findBy(['pilier' => null]);

        $array['nopilier'] = ['entreprises' => $entreprises, 'pilier' => $translator->trans('entreprise.no_pilier', [], 'GeolocationUserBundle')];


        //  Return de la vue avec le tableau
        return $this->render('SiteBundle:ListeInscrits:listeinscrits.html.twig',
            array('datas' => $array)
        );
    }

}
