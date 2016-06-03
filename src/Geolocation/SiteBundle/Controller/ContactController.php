<?php

namespace Geolocation\SiteBundle\Controller;

use Geolocation\AdminBundle\Entity\Contact;
use Geolocation\AdminBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    public function contactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $contact = new Contact();
        $form = $this->createForm(new ContactType(), $contact);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em->persist($contact);
            $em->flush();

            $message = \Swift_Message::newInstance()
                ->setSubject('Nouvelle demande de Contact')
                ->setFrom($contact->getEmail())
                ->setTo('georic.ain@gmail.com')
                ->setBody(
                    $this->renderView(
                        '@Site/Email/contact.html.twig',
                        array('contact' => $contact)
                    ),
                    'text/html'
                )
            ;
            $this->get('mailer')->send($message);
            
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('contact.form.success', [], 'contact'));

            return $this->redirectToRoute('site_contact');
        }
        
        return $this->render('SiteBundle:Contact:contact.html.twig', ['form' => $form->createView()]);
    }
}
