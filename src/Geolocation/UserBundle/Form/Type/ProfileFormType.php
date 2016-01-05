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

class ProfileFormType extends AbstractType {

    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class) {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        if (class_exists('Symfony\Component\Security\Core\Validator\Constraints\UserPassword')) {
            $constraint = new UserPassword();
        } else {
            // Symfony 2.1 support with the old constraint class
            $constraint = new OldUserPassword();
        }

        $this->buildUserForm($builder, $options);

        $builder->add('current_password', 'password', array(
            'label' => 'form.current_password',
            'translation_domain' => 'MediamotionUserBundle',
            'mapped' => false,
            'constraints' => $constraint,
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'profile',
        ));
    }

    public function getName() {
        return 'mediamotion_user_profile';
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options) {
        $countries = Intl::getRegionBundle()->getCountryNames();
        $builder
                ->add('surname', null, array('label' => 'form.surname', 'translation_domain' => 'MediamotionUserBundle'))
                ->add('name', null, array('label' => 'form.name', 'translation_domain' => 'MediamotionUserBundle'))
                ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'MediamotionUserBundle'))
                ->add('file', null, array('label' => 'form.profilPic', 'translation_domain' => 'MediamotionUserBundle'))
                ->add('adresse', 'textarea', array(
                    'label' => 'form.adresse',
                    'required' => false,
                    'translation_domain' => 'MediamotionUserBundle')
                )
                ->add('ville', null, array('label' => 'form.ville', 'translation_domain' => 'MediamotionUserBundle'))
                ->add('codepostal', null, array('label' => 'form.codepostal', 'translation_domain' => 'MediamotionUserBundle'))
                ->add('gender', 'choice', array(
                    'choices' => array('0' => 'Masculin', '1' => 'Féminin'),
                    'required' => true,
                    'expanded' => true,
                    'multiple' => false,
                    'translation_domain' => 'MediamotionUserBundle',
                    'label' => 'form.gender'
                ))
                ->add('pays', 'country', array(
                    'choices' => $countries,
                    'translation_domain' => 'MediamotionUserBundle',
                    'label' => 'form.pays'
                ))
                ->add('save', 'submit', array());
    }

}
