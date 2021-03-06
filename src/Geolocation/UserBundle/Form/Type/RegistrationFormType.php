<?php

namespace Geolocation\UserBundle\Form\Type;

use Geolocation\AdminBundle\Form\PilierType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Ajoute les champs au formulaire
        $builder
            ->add('nom', 'text', array('label' => 'form.nom', 'required' => true, 'translation_domain' => 'GeolocationUserBundle'))
            ->add('codePostal', 'text', array('label' => 'form.cp', 'required' => true, 'translation_domain' => 'GeolocationUserBundle'))
            ->add('ville', 'text', array('label' => 'form.ville', 'required' => true, 'translation_domain' => 'GeolocationUserBundle'))
            ->add('email', 'email', array('label' => 'form.email', 'required' => true, 'translation_domain' => 'GeolocationUserBundle'))
            ->add('username', 'text', array('label' => 'form.username', 'required' => true, 'translation_domain' => 'GeolocationUserBundle'))
            ->add('tel', 'text', array('label' => 'form.tel', 'required' => true, 'translation_domain' => 'GeolocationUserBundle'))
            ->add('isPublic', null, array('label' => 'form.public', 'translation_domain' => 'GeolocationUserBundle'))
            ->add('fileKbis', null, array('label' => 'form.kbis', 'required' => false, 'translation_domain' => 'GeolocationUserBundle'))
            ->add('adresse', 'textarea', array(
                    'label' => 'form.adresse',
                    'required' => true,
                    'translation_domain' => 'GeolocationUserBundle')
            )
            ->add('dateCreationEntreprise', 'date', array(
                'input' => 'datetime',
                'label' => 'form.dateCreationEntreprise',
                'translation_domain' => 'GeolocationUserBundle',
                'format' => 'd/MM/y',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'form-control input-inline datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'DD/MM/Y'
                ]
            ))
            ->add('siren', 'text', array('label' => 'form.siren', 'required' => true, 'translation_domain' => 'GeolocationUserBundle'))
            ->add('url', 'text', array('label' => 'form.url', 'required' => false, 'translation_domain' => 'GeolocationUserBundle'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'required' => false,
                'options' => array('translation_domain' => 'GeolocationUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('description', 'textarea', array(
                    'label' => 'form.description',
                    'required' => false,
                    'translation_domain' => 'GeolocationUserBundle')
            )
            ->add('pilier', null, ['label' => 'form.pilier', 'required' => false, 'translation_domain' => 'GeolocationUserBundle'])
            ->add('rna', 'text', array('label' => 'form.rna', 'required' => false, 'translation_domain' => 'GeolocationUserBundle'));
    }

    public function getName()
    {
        return 'geolocation_user_registration';
    }

}
