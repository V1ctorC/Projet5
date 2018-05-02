<?php

namespace Victor\AdBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand',    ChoiceType::class, array(
                'choices' => array(
                    'Apple' => 'Apple',
                ),
                'label' => 'Marque'
            ))
            ->add('model',    TextType::class, array('label' => 'Modèle'))
            ->add('capacity', ChoiceType::class, array(
                'choices' => array(
                    '64 Go' => '64',
                    '128 Go' => '128',
                    '256 Go' => '256',
                    '512 Go' => '512',
                ),
                'label' => 'Capacité'
            ))
            ->add('color',    TextType::class, array('label' => 'Couleur'))
            ->add('image', ImageType::class, array('label' => 'Image'))
            ->add('save', SubmitType::class, array('label' => 'Envoyer'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Victor\AdBundle\Entity\Phone'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'victor_adbundle_phone';
    }


}
