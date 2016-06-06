<?php

namespace Geolocation\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SuppressionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('objet', TextType::class, ['label' => 'suppression.form.objet', 'required' => false])
            ->add('message', TextareaType::class, ['label' => 'suppression.form.message', 'required' => false])
            ->add('submit', SubmitType::class, ['label' => 'suppression.form.submit'])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geolocation\AdminBundle\Entity\Suppression',
            'translation_domain' => 'suppression'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'geolocation_adminbundle_suppression';
    }
}
