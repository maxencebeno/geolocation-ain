<?php

namespace Geolocation\AdminBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdresseType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('main', null, array('label' => 'form.main', 'translation_domain' => 'GeolocationUserBundle'))*/
            ->add('nom', 'text', array('label' => 'form.nom_site', 'translation_domain' => 'GeolocationUserBundle', 'required' => true))
            ->add('adresse', 'text', array('label' => 'form.adresse', 'translation_domain' => 'GeolocationUserBundle', 'required' => true))
            ->add('ville', 'text', array('label' => 'form.ville', 'translation_domain' => 'GeolocationUserBundle', 'required' => true))
            ->add('codePostal', 'text', array('label' => 'form.cp', 'translation_domain' => 'GeolocationUserBundle', 'required' => true))
            ->add('tel', null, array('label' => 'form.tel', 'translation_domain' => 'GeolocationUserBundle', 'required' => true))
            ->add('isPublic', null, array('label' => 'form.public', 'translation_domain' => 'GeolocationUserBundle'))
            ->add('pilier', null, ['label' => 'form.pilier', 'required' => false, 'translation_domain' => 'GeolocationUserBundle'])
            ->add('iso', EntityType::class, ['label' => 'ISO', 'class' => 'Geolocation\AdminBundle\Entity\Iso', 'multiple' => true, 'expanded' => true])
            ->add('saveSite', 'submit', array('label' => 'form.valider_site', 'translation_domain' => 'GeolocationUserBundle'));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geolocation\AdminBundle\Entity\Adresse'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'geolocation_adminbundle_adresse';
    }

}
