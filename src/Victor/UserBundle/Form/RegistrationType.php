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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            'label' => 'Sexe',
        ));
        $builder->add('firstname',TextType::class, array('label' => 'Prénom'));
        $builder->add('lastname', TextType::class, array('label' => 'Nom'));
        $builder->add('address', TextType::class, array('label' => 'Adresse'));
        $builder->add('zipcode', IntegerType::class, array('label' => 'Code Postal'));
        $builder->add('city', TextType::class, array('label' => 'Ville'));
        $builder->add('country', ChoiceType::class, array(
            'choices' => array(
                'France' => 'France',
            ),
            'label' => 'Pays'
        ));
        $builder->add('phone', TextType::class, array('label' => 'Téléphone portable'));

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