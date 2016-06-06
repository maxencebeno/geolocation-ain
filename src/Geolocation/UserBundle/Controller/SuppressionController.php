<?php
/**
 * Created by PhpStorm.
 * User: maxencebeno
 * Date: 03/06/2016
 * Time: 11:50
 */

namespace Geolocation\UserBundle\Controller;


use Geolocation\AdminBundle\Entity\Suppression;
use Geolocation\AdminBundle\Form\SuppressionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SuppressionController extends Controller
{
    public function demandeSuppressionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $suppression = new Suppression();
        $form = $this->createForm(new SuppressionType(), $suppression);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();

            $suppression->setUser($user);

            $em->persist($suppression);
            $em->flush();

            $message = \Swift_Message::newInstance()
                ->setSubject('Nouvelle demande de Suppression de compte')
                ->setFrom($suppression->getUser()->getEmail())
                ->setTo('georic.ain@gmail.com')
                ->setBody(
                    $this->renderView(
                        '@GeolocationUser/Email/delete.html.twig',
                        array('suppression' => $suppression)
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('suppression.form.success', [], 'suppression'));

            return $this->redirectToRoute('fos_user_profile_edit');
        }

        return $this->render('GeolocationUserBundle:Suppression:suppression.html.twig', ['form' => $form->createView()]);
    }
}