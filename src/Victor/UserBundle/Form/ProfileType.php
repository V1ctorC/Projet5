<?php

namespace Victor\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType

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
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    /**
     * {@inheritdoc}
    */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fos_user_profile_edit';
    }
}

