<?php

namespace Geolocation\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'contact.form.nom', 'required' => false])
            ->add('prenom', TextType::class, ['label' => 'contact.form.prenom', 'required' => false])
            ->add('email', EmailType::class, ['label' => 'contact.form.email', 'required' => true])
            ->add('objet', TextType::class, ['label' => 'contact.form.objet', 'required' => true])
            ->add('message', TextareaType::class, ['label' => 'contact.form.message', 'required' => true])
            ->add('submit', SubmitType::class, ['label' => 'contact.form.submit'])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geolocation\AdminBundle\Entity\Contact',
            'translation_domain' => 'contact'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'geolocation_adminbundle_contact';
    }
}
