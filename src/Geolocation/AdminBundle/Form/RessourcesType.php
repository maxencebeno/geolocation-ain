<?php

namespace Geolocation\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RessourcesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('besoin', 'choice', [
                'label' => 'Besoin',
                'choices' =>
                    [
                        '0' => 'Je propose',
                        '1' => 'J\'ai besoin'
                    ],
                'expanded' => true,
                'multiple' => false])
            ->add('cpf')
            ->add('saveRessource', 'submit', array());
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geolocation\AdminBundle\Entity\Ressources'
        ));
    }

    public function getName()
    {
        return 'geolocation_adminbundle_ressources';
    }
}
