<?php

namespace Mediamotion\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        // Ajoute les champs au formulaire
        $builder
                ->add('name', 'text', array('label' => 'form.name', 'required' => false, 'translation_domain' => 'MediamotionUserBundle'))
                ->add('surname', 'text', array('label' => 'form.surname', 'required' => false, 'translation_domain' => 'MediamotionUserBundle'))
                ->add('email', 'email', array('label' => 'form.email', 'required' => false, 'translation_domain' => 'MediamotionUserBundle'))
                ->add('username', 'text', array('label' => 'form.username', 'required' => false, 'translation_domain' => 'MediamotionUserBundle'))
                ->add('adresse', 'text', array('label' => 'form.adresse', 'required' => true))
                /*->add('gender', 'choice', array(
                    'choices' => array('0' => 'Masculin', '1' => 'Féminin'),
                    'required' => true,
                    'expanded' => true,
                    'multiple' => false,
                    'translation_domain' => 'MediamotionUserBundle',
                    'label' => 'form.gender',
        ))*/;
        
    }

    public function getName() {
        return 'mediamotion_user_registration';
    }

}
