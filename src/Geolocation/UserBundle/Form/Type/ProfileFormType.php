<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geolocation\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Intl\Intl;

class ProfileFormType extends AbstractType
{

    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (class_exists('Symfony\Component\Security\Core\Validator\Constraints\UserPassword')) {
            $constraint = new UserPassword();
        } else {
            // Symfony 2.1 support with the old constraint class
            $constraint = new OldUserPassword();
        }

        $this->buildUserForm($builder, $options);

        $builder->add('current_password', 'password', array(
            'label' => 'form.current_password',
            'translation_domain' => 'GeolocationUserBundle',
            'mapped' => false,
            'constraints' => $constraint,
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'profile',
        ));
    }

    public function getName()
    {
        return 'geolocation_user_profile';
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $countries = Intl::getRegionBundle()->getCountryNames();
        $builder
            ->add('nom', null, array('label' => 'form.nom', 'translation_domain' => 'GeolocationUserBundle', 'required' => true, 'read_only' => true))
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'GeolocationUserBundle', 'required' => true))
            ->add('adresse', 'text', array('label' => 'form.adresse', 'translation_domain' => 'GeolocationUserBundle', 'required' => true))
            ->add('ville', 'text', array('label' => 'form.ville', 'translation_domain' => 'GeolocationUserBundle', 'required' => true))
            ->add('codePostal', null, array('label' => 'form.cp', 'translation_domain' => 'GeolocationUserBundle', 'required' => true))
            ->add('tel', null, array('label' => 'form.tel', 'translation_domain' => 'GeolocationUserBundle', 'required' => true))
            ->add('dateCreationEntreprise', 'date', array(
                'input' => 'datetime',
                'label' => 'form.dateCreationEntreprise',
                'translation_domain' => 'GeolocationUserBundle',
                'format' => 'd/MM/y',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control input-inline datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'DD/MM/Y'
                ]
            ))
            ->add('pilier', null, ['label' => 'form.pilier', 'translation_domain' => 'GeolocationUserBundle', 'required' => false])
            ->add('siret', null, array('label' => 'form.siret', 'translation_domain' => 'GeolocationUserBundle', 'required' => true, 'read_only' => true))
            ->add('fileKbis', null, array('label' => 'form.kbis', 'translation_domain' => 'GeolocationUserBundle'))
            ->add('url', null, array('label' => 'form.url', 'translation_domain' => 'GeolocationUserBundle'))
            ->add('description', 'textarea', array('label' => 'form.description', 'translation_domain' => 'GeolocationUserBundle', 'required' => false))
            ->add('save', 'submit', array('label' => 'form.valider', 'translation_domain' => 'GeolocationUserBundle'));
    }

}
