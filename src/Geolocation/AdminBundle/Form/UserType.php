<?php

namespace Geolocation\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('codePostal')
            ->add('ville')
            ->add('tel')
            ->add('dateCreation', 'date', array(
                'input' => 'datetime',
                'label' => 'form.dateCreation',
                'translation_domain' => 'GeolocationUserBundle',
                'format' => 'd/MM/y',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control input-inline datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'DD/MM/Y',
                    'id' => 'datetimepicker1'
                ]
            ))
            ->add('dateCreationEntreprise', 'date', array(
                'input' => 'datetime',
                'label' => 'form.dateCreationEntreprise',
                'translation_domain' => 'GeolocationUserBundle',
                'format' => 'd/MM/y',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control input-inline datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'DD/MM/Y',
                    'id' => 'datetimepicker2'
                ]
            ))
            ->add('siret')
            ->add('kbis')
            ->add('url')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geolocation\AdminBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'geolocation_adminbundle_user';
    }
}
