<?php

namespace Geolocation\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;

use Geolocation\AdminBundle\Entity\User;
use Geolocation\AdminBundle\Form\UserType;
use Geolocation\AdminBundle\Form\UserFilterType;

/**
 * User controller.
 *
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     */
    public function indexAction()
    {
        list($filterForm, $queryBuilder) = $this->filter();

        list($entities, $pagerHtml) = $this->paginator($queryBuilder);

        return $this->render('GeolocationAdminBundle:User:index.html.twig', array(
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
        $filterForm = $this->createForm(new UserFilterType());
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('GeolocationAdminBundle:User')->createQueryBuilder('e');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('UserControllerFilter');
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
                $session->set('UserControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('UserControllerFilter')) {
                $filterData = $session->get('UserControllerFilter');
                $filterForm = $this->createForm(new UserFilterType(), $filterData);
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
        $routeGenerator = function ($page) use ($me) {
            return $me->generateUrl('user', array('page' => $page));
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
     * Creates a new User entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createForm(new UserType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

            return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
        }

        return $this->render('GeolocationAdminBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction()
    {
        $entity = new User();
        $form = $this->createForm(new UserType(), $entity);

        return $this->render('GeolocationAdminBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeolocationAdminBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('GeolocationAdminBundle:User:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeolocationAdminBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('GeolocationAdminBundle:User:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $fullUrl = $this->generateUrl('site_homepage', $params = array(), true);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeolocationAdminBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        if (!$entity->isEnabled()) {
            $enabled = false;
        } else {
            $enabled = true;
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UserType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {

            if ($enabled === false && $entity->isEnabled()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject('BIENVENUE SUR GEORIC')
                    ->setFrom(['georic.ain@gmail.com' => "GEORIC"])
                    ->setTo($entity->getEmail())
                    ->setBody(
                        $this->renderView(
                            '@GeolocationAdmin/Email/activation_account.html.twig',
                            array('user' => $entity, 'url' => $fullUrl)
                        ),
                        'text/html'
                    )
                    ->setContentType("text/html");

                // Envoit du mail
                $this->get('mailer')->send($message);
            }
            
            $userManager->updateUser($entity);

            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.update.success');

            return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.update.error');
        }

        return $this->render('GeolocationAdminBundle:User:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GeolocationAdminBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('user'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }

    public function downloadFileAction(Request $request)
    {
        // On récupère l'utilisateur
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('GeolocationAdminBundle:User')
            ->findOneBy(array(
                'id' => $request->attributes->get('id')
            ));

        $path = $this->get('kernel')->getRootDir() . "/../web/uploads/kbis/" . $user->getKbis();
        $content = file_get_contents($path);

        $response = new Response();

        $response->headers->set('Content-Type', 'application/pdf');

        $response->setContent($content);
        return $response;
    }

    public function toggleActivationAction(Request $request)
    {
        $fullUrl = $this->generateUrl('site_homepage', $params = array(), true);

        // On récupère l'utilisateur
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('GeolocationAdminBundle:User')
            ->findOneBy(array(
                'id' => $request->attributes->get('id')
            ));


        $user->isEnabled() ? $user->setEnabled(0) : $user->setEnabled(1);

        $em->persist($user);
        $em->flush();

        if ($user->isEnabled()) {
            $message = \Swift_Message::newInstance()
                ->setSubject('BIENVENUE SUR GEORIC')
                ->setFrom(['georic.ain@gmail.com' => "GEORIC"])
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        '@GeolocationAdmin/Email/activation_account.html.twig',
                        array('user' => $user, 'url' => $fullUrl)
                    ),
                    'text/html'
                )
                ->setContentType("text/html");

            // Envoit du mail
            $this->get('mailer')->send($message);
        }

        $this->addFlash(
            'notice',
            $user->isEnabled() ? "L'utilisateur a bien été activé" : "L'utilisateur a bien été désactivé"
        );


        return $this->redirectToRoute('user');
    }
}
