<?php

namespace Geolocation\AdminBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', [
                'label' => 'Title de référencement',
                'attr' => [
                    'style' => 'width:100%;'
                ]
            ])
            ->add('titre', 'text', [
                'label' => 'Titre de la page',
                'attr' => [
                    'style' => 'width:100%;'
                ]])
            ->add('description', 'textarea', [
                'attr' => [
                    'style' => 'width:100%;height:50px;'
                ]
            ])
            ->add('content', CKEditorType::class, array(
                'label' => 'Contenu'
            ))
            ->add('url', 'text', [
                'attr' => [
                    'style' => 'width:100%;'
                ]
            ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geolocation\AdminBundle\Entity\Page'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'geolocation_adminbundle_page';
    }
}
