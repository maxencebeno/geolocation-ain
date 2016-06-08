<?php

namespace Geolocation\AdminBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class RessourcesFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('besoin', 'filter_choice', [
                'label' => 'Est-ce un besoin ou un proposition ?',
                'choices' => [
                    '0' => 'Proposition',
                    '1' => 'Besoin'
            ]])
            ->add('id', 'number', ['required' => false])
            ->add('user', EntityType::class, [
                'required' => false,
                'class' => 'Geolocation\AdminBundle\Entity\User',
                'placeholder' => ""
            ])
            ->add('cpf', EntityType::class, [
                'required' => false,
                'class' => 'Geolocation\AdminBundle\Entity\Cpf',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->join('GeolocationAdminBundle:Ressources', 'r', 'WITH', 'c.id = r.cpf')
                        ;
                },
                'placeholder' => ""
            ])
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
        return 'geolocation_adminbundle_ressourcesfiltertype';
    }
}
