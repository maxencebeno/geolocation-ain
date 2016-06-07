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
                'label' => 'form.besoin_label',
                'choices' =>
                    [
                        '0' => 'form.proposition',
                        '1' => 'form.besoin'
                    ],
                'expanded' => true,
                'multiple' => false,
                'translation_domain' => 'ressources'])
            //->add('cpf')
            ->add('description', TextareaType::class, ['label' => 'form.description', 'attr' => ['placeholder' => "form.placeholder.description"], 'translation_domain' => 'ressources','required' => false])
            ->add('remarque', TextareaType::class, ['label' => 'form.remarques', 'attr' => ['placeholder' => "form.placeholder.remarques"], 'translation_domain' => 'ressources','required' => false])
            ->add('quantite', TextareaType::class, ['label' => 'form.quantite', 'attr' => ['placeholder' => "form.placeholder.quantite"],'translation_domain' => 'ressources', 'required' => false])
            ->add('saveRessource', 'submit', array('label' => 'form.valider_ressource', 'translation_domain' => 'ressources'));
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
