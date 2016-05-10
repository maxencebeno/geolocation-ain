<?php

namespace Geolocation\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class UserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'filter_number_range')
            ->add('nom', 'filter_text')
            ->add('adresse', 'filter_text')
            ->add('codePostal', 'filter_number_range')
            ->add('ville', 'filter_text')
            ->add('tel', 'filter_text')
            ->add('dateCreation', 'filter_date_range')
            ->add('dateCreationEntreprise', 'filter_date_range')
            ->add('siren', 'filter_text')
            ->add('kbis', 'filter_text')
            ->add('url', 'filter_text')
        ;

        $listener = function(FormEvent $event)
        {
            // Is data empty?
            foreach ($event->getData() as $data) {
                if(is_array($data)) {
                    foreach ($data as $subData) {
                        if(!empty($subData)) return;
                    }
                }
                else {
                    if(!empty($data)) return;
                }
            }

            $event->getForm()->addError(new FormError('Filter empty'));
        };
        $builder->addEventListener(FormEvents::POST_BIND, $listener);
    }

    public function getName()
    {
        return 'geolocation_adminbundle_userfiltertype';
    }
}
