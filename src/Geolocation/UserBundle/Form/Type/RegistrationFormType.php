<?php

namespace Geolocation\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        // Ajoute les champs au formulaire
        $builder
                ->add('nom', 'text', array('label' => 'form.nom', 'required' => false, 'translation_domain' => 'GeolocationUserBundle'))
                ->add('codePostal', 'email', array('label' => 'form.cp', 'required' => false, 'translation_domain' => 'GeolocationUserBundle'))
                ->add('ville', 'text', array('label' => 'form.ville', 'required' => false, 'translation_domain' => 'GeolocationUserBundle'))
                ->add('email', 'email', array('label' => 'form.email', 'required' => false, 'translation_domain' => 'GeolocationUserBundle'))
                ->add('username', 'text', array('label' => 'form.username', 'required' => false, 'translation_domain' => 'GeolocationUserBundle'))
                ->add('tel', 'text', array('label' => 'form.tel', 'required' => true, 'translation_domain' => 'GeolocationUserBundle'))
                ->add('fileKbis', null, array('label' => 'form.kbis', 'translation_domain' => 'GeolocationUserBundle'))
                ->add('adresse', 'textarea', array(
                    'label' => 'form.adresse',
                    'required' => false,
                    'translation_domain' => 'GeolocationUserBundle')
                )
                ->add('siret', 'text', array('label' => 'form.siret', 'required' => true, 'translation_domain' => 'GeolocationUserBundle'))
                ->add('url', 'text', array('label' => 'form.url', 'required' => true, 'translation_domain' => 'GeolocationUserBundle'))
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'required' => false,
                    'options' => array('translation_domain' => 'GeolocationUserBundle'),
                    'first_options' => array('label' => 'form.password'),
                    'second_options' => array('label' => 'form.password_confirmation'),
                    'invalid_message' => 'fos_user.password.mismatch',
                ))
                ;
        
    }

    public function getName() {
        return 'geolocation_user_registration';
    }

}
