<?php

namespace Geolocation\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class SiteFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'number', ['required' => false])
            ->add('siret', 'filter_text')
            ->add('ville', 'filter_text')
            ->add('codePostal', 'filter_text')
            ->add('isPublic', 'filter_choice', [
                'label' => 'N° de téléphone visible par tous',
                'choices' => [
                    '0' => 'Non',
                    '1' => 'Oui'
                ]
            ])
            ->add('nom', 'filter_text')
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
        return 'geolocation_adminbundle_sitefiltertype';
    }
}
