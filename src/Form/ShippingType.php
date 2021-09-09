<?php

// src/AppBundle/Form/TaskType.php
namespace Thecoderpsshipping\Form;

use Symfony\Component\Form\Extension\Core\Type\{CheckboxType, ChoiceType, IntegerType, NumberType, SubmitType, TextType};
use Symfony\Component\Form\{FormBuilderInterface, AbstractType};

class ShippingType extends AbstractType
{
    private function cityFromDb()
    {
        $sql = '
        SELECT * FROM `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping` WHERE `active` = 1
        ';

        return \Db::getInstance()->executeS($sql);
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $result = $this->cityFromDb();

        $citys = array();

        foreach ($result as $city) {
            $citys[$city['city_name']] = $city['id_thecoderpsshipping'];
        }

        $builder
            ->add('thecoderpsshipping_id', ChoiceType::class, [
                'label' => 'City',
                'choices' => $citys,
                'required' => true
            ])
            ->add('price', NumberType::class, [
                'label' => 'Price',
                'required' => true
            ])
            ->add('active', CheckboxType::class, [
                'required' => false
            ])
            ->add('save', SubmitType::class);
    }
}