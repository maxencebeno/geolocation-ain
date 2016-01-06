<?php

namespace Geolocation\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        // Ajoute les champs au formulaire
        $builder
                ->add('name', 'text', array('label' => 'form.name', 'required' => false, 'translation_domain' => 'GeolocationUserBundle'))
                ->add('surname', 'text', array('label' => 'form.surname', 'required' => false, 'translation_domain' => 'GeolocationUserBundle'))
                ->add('email', 'email', array('label' => 'form.email', 'required' => false, 'translation_domain' => 'GeolocationUserBundle'))
                ->add('username', 'text', array('label' => 'form.username', 'required' => false, 'translation_domain' => 'GeolocationUserBundle'))
                ->add('adresse', 'text', array('label' => 'form.adresse', 'required' => true))
                ;
        
    }

    public function getName() {
        return 'geolocation_user_registration';
    }

}
