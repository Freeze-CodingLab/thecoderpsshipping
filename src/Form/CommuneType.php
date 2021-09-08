<?php

// src/AppBundle/Form/TaskType.php
namespace Thecoderpsshipping\Form;

use Symfony\Component\Form\Extension\Core\Type\{CheckboxType, SubmitType, TextType};
use Symfony\Component\Form\{FormBuilderInterface, AbstractType};

class CommuneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('commune_name', TextType::class, [
                'label' => 'Commune name',
                'required' => true
            ])
            ->add('active', CheckboxType::class, [
                'required' => false
            ])
            ->add('save', SubmitType::class);
    }
}