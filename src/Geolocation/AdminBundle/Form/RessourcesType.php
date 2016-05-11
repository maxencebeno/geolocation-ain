<?php

namespace Geolocation\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('description', TextareaType::class, ['label' => 'Description', 'attr' => ['placeholder' => "Description complémentaire du produit"], 'required' => false])
            ->add('remarque', TextareaType::class, ['label' => 'Remarques / observations', 'attr' => ['placeholder' => "Précisez ici toutes les informations qui permettront de faciliter la transaction"], 'required' => false])
            ->add('saveRessource', 'submit', array('label' => 'form.valider_ressource', 'translation_domain' => 'GeolocationUserBundle'));
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
