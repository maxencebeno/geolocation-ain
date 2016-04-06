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
        $sectionId = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();

        $cpfs = $em->getRepository('GeolocationAdminBundle:Cpf')
            ->findBy(array(
                'section' => $sectionId
            ));

        $divisions = [];

        $done = [];

        foreach ($cpfs as $cpf) {
            if ($request->query->get('filter') === "true") {
                $users = $em->getRepository('GeolocationAdminBundle:Ressources')
                    ->findBy([
                        'cpf' => $cpf
                    ]);
            } else {
                $users = [];
            }
            if ($request->query->get('filter') === "true") {
                if ($cpf->getDivision() !== null) {
                    if (!in_array($cpf->getDivision()->getId(), $done)) {
                        $divisions[] = $cpf->getDivision();
                        $done[] = $cpf->getDivision()->getId();
                    }
                }
            } else {
                if ($cpf->getDivision() !== null) {
                    if (!in_array($cpf->getDivision()->getId(), $done)) {
                        $divisions[] = $cpf->getDivision();
                        $done[] = $cpf->getDivision()->getId();
                    }
                }
            }
        }

        $normalizer = new GetSetMethodNormalizer();
        $serializer = new Serializer(array($normalizer), array('division' => new JsonEncoder()));
        $jsonContent = $serializer->serialize($divisions, 'json');
        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function getGroupeAction(Request $request) {
        $sectionId = $request->query->get('sectionid');
        $divisionId = $request->query->get('divisionid');

        $em = $this->getDoctrine()->getManager();

        $section = $em->getRepository('GeolocationAdminBundle:Section')
            ->findOneBy([
                'id' => $sectionId
            ]);

        $division = $em->getRepository('GeolocationAdminBundle:Division')
            ->findOneBy([
                'id' => $divisionId
            ]);

        $cpfs = $em->getRepository('GeolocationAdminBundle:Cpf')
            ->findBy(array(
                'section' => $section,
                'division' => $division
            ));

        $groupes = [];

        $done = [];

        foreach ($cpfs as $cpf) {
            if ($request->query->get('filter') === "true") {
                $users = $em->getRepository('GeolocationAdminBundle:Ressources')
                    ->findBy([
                        'cpf' => $cpf
                    ]);
            } else {
                $users = [];
            }
            if ($request->query->get('filter') === "true") {
                if ($cpf->getGroupe() !== null) {
                    if (!in_array($cpf->getGroupe()->getId(), $done)) {
                        $groupes[] = $cpf->getGroupe();
                        $done[] = $cpf->getGroupe()->getId();
                    }
                }
            } else {
                if ($cpf->getGroupe() !== null) {
                    if (!in_array($cpf->getGroupe()->getId(), $done)) {
                        $groupes[] = $cpf->getGroupe();
                        $done[] = $cpf->getGroupe()->getId();
                    }
                }
            }
        }

        $normalizer = new GetSetMethodNormalizer();
        $serializer = new Serializer(array($normalizer), array('groupe' => new JsonEncoder()));
        $jsonContent = $serializer->serialize($groupes, 'json');
        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function getClasseAction(Request $request) {
        $sectionId = $request->query->get('sectionid');
        $divisionId = $request->query->get('divisionid');
        $groupeId = $request->query->get('groupeid');
        $em = $this->getDoctrine()->getManager();

        $cpfs = $em->getRepository('GeolocationAdminBundle:Cpf')
            ->findBy(array(
                'section' => $sectionId,
                'division' => $divisionId,
                'groupe' => $groupeId
            ));

        $classes = [];

        $done = [];

        foreach ($cpfs as $cpf) {
            if ($request->query->get('filter') === "true") {
                $users = $em->getRepository('GeolocationAdminBundle:Ressources')
                    ->findBy([
                        'cpf' => $cpf
                    ]);
            } else {
                $users = [];
            }
            if ($request->query->get('filter') === "true") {
                if ($cpf->getClasse() !== null) {
                    if (!in_array($cpf->getClasse()->getId(), $done)) {
                        $classes[] = $cpf->getClasse();
                        $done[] = $cpf->getClasse()->getId();
                    }
                }
            } else {
                if ($cpf->getClasse() !== null) {
                    if (!in_array($cpf->getClasse()->getId(), $done)) {
                        $classes[] = $cpf->getClasse();
                        $done[] = $cpf->getClasse()->getId();
                    }
                }
            }
        }

        $normalizer = new GetSetMethodNormalizer();
        $serializer = new Serializer(array($normalizer), array('classe' => new JsonEncoder()));
        $jsonContent = $serializer->serialize($classes, 'json');
        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function getCategorieAction(Request $request) {
        $sectionId = $request->query->get('sectionid');
        $divisionId = $request->query->get('divisionid');
        $groupeId = $request->query->get('groupeid');
        $classeId = $request->query->get('classeid');
        $em = $this->getDoctrine()->getManager();

        $cpfs = $em->getRepository('GeolocationAdminBundle:Cpf')
            ->findBy(array(
                'section' => $sectionId,
                'division' => $divisionId,
                'groupe' => $groupeId,
                'classe' => $classeId
            ));

        $categories = [];

        $done = [];

        foreach ($cpfs as $cpf) {
            $users = $em->getRepository('GeolocationAdminBundle:Ressources')
                ->findBy([
                    'cpf' => $cpf
                ]);
            if ($request->query->get('filter') === "true") {
                $users = $em->getRepository('GeolocationAdminBundle:Ressources')
                    ->findBy([
                        'cpf' => $cpf
                    ]);
            } else {
                $users = [];
            }
            if ($request->query->get('filter') === "true") {
                if ($cpf->getCategorie() !== null) {
                    if (!in_array($cpf->getCategorie()->getId(), $done)) {
                        $categories[] = $cpf->getCategorie();
                        $done[] = $cpf->getCategorie()->getId();
                    }
                }
            } else {
                if ($cpf->getCategorie() !== null) {
                    if (!in_array($cpf->getCategorie()->getId(), $done)) {
                        $categories[] = $cpf->getCategorie();
                        $done[] = $cpf->getCategorie()->getId();
                    }
                }
            }
        }

        $normalizer = new GetSetMethodNormalizer();
        $serializer = new Serializer(array($normalizer), array('categorie' => new JsonEncoder()));
        $jsonContent = $serializer->serialize($categories, 'json');
        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function getSousCategorieAction(Request $request) {
        $sectionId = $request->query->get('sectionid');
        $divisionId = $request->query->get('divisionid');
        $groupeId = $request->query->get('groupeid');
        $classeId = $request->query->get('classeid');
        $categorieId = $request->query->get('categorieid');
        $em = $this->getDoctrine()->getManager();

        $cpfs = $em->getRepository('GeolocationAdminBundle:Cpf')
            ->findBy(array(
                'section' => $sectionId,
                'division' => $divisionId,
                'groupe' => $groupeId,
                'classe' => $classeId,
                'categorie' => $categorieId
            ));

        $sousCategories = [];

        $done = [];

        foreach ($cpfs as $cpf) {
            if ($request->query->get('filter') === "true") {
                $users = $em->getRepository('GeolocationAdminBundle:Ressources')
                    ->findBy([
                        'cpf' => $cpf
                    ]);
            } else {
                $users = [];
            }
            if ($request->query->get('filter') === "true") {
                if ($cpf->getSouscategorie() !== null) {
                    if (!in_array($cpf->getSouscategorie()->getId(), $done)) {
                        $sousCategories[] = $cpf->getSouscategorie();
                        $done[] = $cpf->getSouscategorie()->getId();
                    }
                }
            } else {
                if ($cpf->getSouscategorie() !== null) {
                    if (!in_array($cpf->getSouscategorie()->getId(), $done)) {
                        $sousCategories[] = $cpf->getSouscategorie();
                        $done[] = $cpf->getSouscategorie()->getId();
                    }
                }
            }
        }

        $normalizer = new GetSetMethodNormalizer();
        $serializer = new Serializer(array($normalizer), array('souscategorie' => new JsonEncoder()));
        $jsonContent = $serializer->serialize($sousCategories, 'json');
        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}