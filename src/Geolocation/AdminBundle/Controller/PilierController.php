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

/**
 * Pilier controller.
 *
 */
class PilierController extends Controller
{
    /**
     * Lists all Pilier entities.
     *
     */
    public function indexAction()
    {
        list($filterForm, $queryBuilder) = $this->filter();

        list($entities, $pagerHtml) = $this->paginator($queryBuilder);

        return $this->render('GeolocationAdminBundle:Pilier:index.html.twig', array(
            'entities' => $entities,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
        ));
    }

    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new PilierFilterType());
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('GeolocationAdminBundle:Pilier')->createQueryBuilder('e');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('PilierControllerFilter');
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
                $session->set('PilierControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('PilierControllerFilter')) {
                $filterData = $session->get('PilierControllerFilter');
                $filterForm = $this->createForm(new PilierFilterType(), $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return array($filterForm, $queryBuilder);
    }

    /**
    * Get results from paginator and get paginator view.
    *
    */
    protected function paginator($queryBuilder)
    {
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $this->getRequest()->get('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me)
        {
            return $me->generateUrl('pilier', array('page' => $page));
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
     * Creates a new Pilier entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Pilier();
        $form = $this->createForm(new PilierType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

            return $this->redirect($this->generateUrl('pilier_show', array('id' => $entity->getId())));
        }

        return $this->render('GeolocationAdminBundle:Pilier:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Pilier entity.
     *
     */
    public function newAction()
    {
        $entity = new Pilier();
        $form   = $this->createForm(new PilierType(), $entity);
        
        $em = $this->getDoctrine()->getManager()->getRepository('GeolocationAdminBundle:Pilier');
    	$piliers = $em->findAll();

        return $this->render('GeolocationAdminBundle:Pilier:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'piliers' => $piliers 
        ));
    }

    /**
     * Finds and displays a Pilier entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeolocationAdminBundle:Pilier')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pilier entity.');
        }

        $entity->setCategorie(PilierRepository::getValueCategorie($entity->getCategorie()));

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('GeolocationAdminBundle:Pilier:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Pilier entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeolocationAdminBundle:Pilier')->find($id);
        
        $em = $this->getDoctrine()->getManager()->getRepository('GeolocationAdminBundle:Pilier');
    	$piliers = $em->findAll();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pilier entity.');
        }

        $editForm = $this->createForm(new PilierType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('GeolocationAdminBundle:Pilier:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'piliers' => $piliers 
        ));
    }

    /**
     * Edits an existing Pilier entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeolocationAdminBundle:Pilier')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pilier entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PilierType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.update.success');

            return $this->redirect($this->generateUrl('pilier_edit', array('id' => $id)));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.update.error');
        }

        return $this->render('GeolocationAdminBundle:Pilier:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Pilier entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GeolocationAdminBundle:Pilier')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pilier entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('pilier'));
    }

    /**
     * Creates a form to delete a Pilier entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
