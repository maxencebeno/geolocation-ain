<?php

namespace Geolocation\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Geolocation\AdminBundle\Repository\PilierRepository;

class PilierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('categorie', 'choice', array(
                'choices' => PilierRepository::getCategorie()
            ))
            ->add('urlPicto')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geolocation\AdminBundle\Entity\Pilier'
        ));
    }

    public function getName()
    {
        return 'geolocation_adminbundle_pilier';
    }
}
