<?php

namespace Geolocation\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;
use Geolocation\AdminBundle\Entity\Adresse;
use Geolocation\AdminBundle\Form\AdresseType;
use Geolocation\AdminBundle\Form\AdresseFilterType;
use Geolocation\AdminBundle\Domain\Api\ApiLib;
use Symfony\Component\Translation\Translator;

/**
 * Adresse controller.
 *
 */
class AdresseController extends Controller {

    /**
     * Lists all Adresse entities.
     *
     */
    public function indexAction() {
        list($filterForm, $queryBuilder) = $this->filter();

        list($entities, $pagerHtml) = $this->paginator($queryBuilder);

        return $this->render('GeolocationAdminBundle:Adresse:index.html.twig', array(
                    'entities' => $entities,
                    'pagerHtml' => $pagerHtml,
                    'filterForm' => $filterForm->createView(),
        ));
    }

    /**
     * Create filter form and process filter request.
     *
     */
    protected function filter() {
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new AdresseFilterType());
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('GeolocationAdminBundle:Adresse')->createQueryBuilder('e')->where('e.main = false');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('AdresseControllerFilter');
        }

        // Filter action
        if ($request->get('filter_action') == 'filter') {
            // Bind values from the request
            $filterForm->bind($request);

            if ($filterForm->isValid()) {
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                // Save filter to session
                $filterData = $filterForm->getData();
                $session->set('AdresseControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('AdresseControllerFilter')) {
                $filterData = $session->get('AdresseControllerFilter');
                $filterForm = $this->createForm(new AdresseFilterType(), $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return array($filterForm, $queryBuilder);
    }

    /**
     * Get results from paginator and get paginator view.
     *
     */
    protected function paginator($queryBuilder) {
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $this->getRequest()->get('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me) {
            return $me->generateUrl('adresse', array('page' => $page));
        };

        // Paginator - view
        $translator = $this->get('translator');
        $view = new TwitterBootstrapView();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => $translator->trans('views.index.pagprev', array(), 'JordiLlonchCrudGeneratorBundle'),
            'next_message' => $translator->trans('views.index.pagnext', array(), 'JordiLlonchCrudGeneratorBundle'),
        ));

        return array($entities, $pagerHtml);
    }

    /**
     * Creates a new Adresse entity.
     *
     */
    public function createAction(Request $request) {
        $translator = new Translator($request->getLocale());
        $entity = new Adresse();
        $form = $this->createForm(new AdresseType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

            return $this->redirect($this->generateUrl('adresse_show', array('id' => $entity->getId())));
        }

        return $this->render('GeolocationAdminBundle:Adresse:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    
    /**
     * Finds and displays a Adresse entity.
     *
     */
    public function showAction(Request $request, $id) {
        $translator = new Translator($request->getLocale());
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeolocationAdminBundle:Adresse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('general.adresse_not_found', [], 'GeolocationAdminBundle'));
        }
        $isoAlreadyIn = $em->getRepository('GeolocationAdminBundle:SiteIso')
                ->findBy([
            'siteId' => $entity
        ]);

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('GeolocationAdminBundle:Adresse:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
                    'isoAlreadyIn' => $isoAlreadyIn
            ));
    }

    /**
     * Displays a form to edit an existing Adresse entity.
     *
     */
    public function editAction(Request $request, $id) {
        $translator = new Translator($request->getLocale());
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeolocationAdminBundle:Adresse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('general.adresse_not_found', [], 'GeolocationAdminBundle'));
        }

        $isoAlreadyIn = $em->getRepository('GeolocationAdminBundle:SiteIso')
                ->findBy([
            'siteId' => $entity
        ]);

        $editForm = $this->createForm(new AdresseType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('GeolocationAdminBundle:Adresse:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'isoAlreadyIn' => $isoAlreadyIn
        ));
    }

    /**
     * Edits an existing Adresse entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $translator = new Translator($request->getLocale());
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeolocationAdminBundle:Adresse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($translator->trans('general.adresse_not_found', [], 'GeolocationAdminBundle'));
        }

        $isoAlreadyIn = $em->getRepository('GeolocationAdminBundle:SiteIso')
                ->findBy([
            'siteId' => $entity
        ]);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AdresseType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            /* $em->persist($entity);
              $em->flush();
              $this->get('session')->getFlashBag()->add('success', 'flash.update.success');
             */

            $adr = $request->request->get('geolocation_adminbundle_adresse');

            $entity->setAdresse($adr['adresse']);
            $entity->setVille($adr['ville']);
            $entity->setCodePostal($adr['codePostal']);

            // On cherche ici la latitude et longitude de l'adresse de l'entreprise pour l'afficher correctement sur la google map
            $response = ApiLib::searchAdresse(null, $entity);

            if ($response == false) {

                $this->addFlash('danger', $translator->trans('address.flash.create.fail.adresse', [], 'GeolocationAdminBundle'));
            } else {
                // Geocode your request
                $datas = $response->all();
                if (count($datas) >= 1) {

                    $verifcp = ApiLib::verifCp(strip_tags($entity->getCodePostal()));

                    if ($verifcp === true) {

                        $data = $datas[0];
                        $latitude = $data->getLatitude();
                        $longitude = $data->getLongitude();

                        $entity->setLatitude($latitude);
                        $entity->setLongitude($longitude);
                        $entity->setTel($adr['tel']);


                        $em->persist($entity);
                        $em->flush();

                        /*$sitesIso = $em->getRepository('GeolocationAdminBundle:SiteIso')
                                ->findBy([
                            'siteId' => $entity,
                        ]);


                        foreach ($sitesIso as $siteIso) {
                            $em->remove($siteIso);
                        }
                        $em->flush();


                        if (array_key_exists('iso', $adr)) {
                            foreach ($adr['iso'] as $i) {
                                $iso = $em->getRepository('GeolocationAdminBundle:Iso')
                                        ->findOneBy(['id' => $i[0]]);

                                $siteIso = new \Geolocation\AdminBundle\Entity\SiteIso();

                                $siteIso->setIsoId($iso);
                                $siteIso->setSiteId($entity);
                                if ($request->request->get('certifie-' . $i) === "oui") {
                                    $siteIso->setCertifie(true);
                                    $siteIso->setEnCoursCertification(false);
                                    if ($request->request->get('date_certification-' . $i) !== null) {
                                        $siteIso->setDateCertification(ApiLib::dateToMySQL($request->request->get('date_certification-' . $i)));
                                    }
                                } else {
                                    $siteIso->setCertifie(false);
                                    $siteIso->setEnCoursCertification(true);
                                }

                                if ($request->request->get('other')) {
                                    $siteIso->setAutre($request->request->get('other'));
                                }

                                $em->persist($siteIso);
                            }
                            $em->flush();
                        }*/
                        $this->addFlash('success', $translator->trans('address.flash.create.success', [], 'adresse'));
                    } else {
                        $this->addFlash('danger', $translator->trans('address.flash.create.fail.cp', [], 'adresse'));
                    }
                } else {
                    $this->addFlash('danger', $translator->trans('address.flash.create.fail.adresse', [], 'adresse'));
                }
            }
        } else {
            $this->get('session')->getFlashBag()->add('danger', 'address.flash.update.error');
        }

        return $this->render('GeolocationAdminBundle:Adresse:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'isoAlreadyIn' => $isoAlreadyIn
        ));
    }

    /**
     * Deletes a Adresse entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $translator = new Translator($request->getLocale());
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GeolocationAdminBundle:Adresse')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Adresse entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('danger', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('adresse'));
    }

    /**
     * Creates a form to delete a Adresse entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
