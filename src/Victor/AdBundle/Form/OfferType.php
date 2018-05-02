<?php

namespace Victor\AdBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price',    IntegerType::class, array('label' => 'Prix'))
            ->add('status',  ChoiceType::class, array(
                'choices' => array(
                    'Comme neuf' => 'Comme neuf',
                    'Très bon état' => 'Très bon état',
                    'Bon état' => 'Bon état',
                    'Correct' => 'Correct',
                ),
                "label" => "Etat"
            ))
            ->add('description', TextType::class, array('label' => 'Description'))
            ->add('save',   SubmitType::class, array('label' => 'Envoyer'));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Victor\AdBundle\Entity\Offer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'victor_adbundle_offer';
    }


}
