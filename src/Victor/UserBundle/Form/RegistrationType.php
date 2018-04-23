<?php
/**
 * Created by PhpStorm.
 * User: VictorChevalier
 * Date: 11/04/2018
 * Time: 14:58
 */

namespace Victor\UserBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('gender', ChoiceType::class, array(
            'choices' => array(
                'Homme' => 'Homme',
                'Femme' => 'Femme',
            ),
        ));
        $builder->add('firstname');
        $builder->add('lastname');
        $builder->add('address');
        $builder->add('zipcode');
        $builder->add('city');
        $builder->add('country', ChoiceType::class, array(
            'choices' => array(
                'France' => 'France',
            )
        ));
        $builder->add('phone');

    }

    public function getParent()

    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()

    {
        return 'app_user_registration';
    }

    public function getName()

    {
        return $this->getBlockPrefix();
    }
}