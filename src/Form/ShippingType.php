<?php

// src/AppBundle/Form/TaskType.php
namespace Thecoderpsshipping\Form;

use PrestaShop\PrestaShop\Core\Import\EntityField\EntityField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType, ChoiceType, IntegerType, NumberType, SubmitType, TextType};
use Symfony\Component\Form\{FormBuilderInterface, AbstractType};
use Thecoderpsshipping\Entity\Thecoderpsshipping;
use Thecoderpsshipping\Repository\ThecoderpsshippingRepository;

class ShippingType extends AbstractType
{
    // private function cityFromDb()
    // {
    //     $sql = '
    //     SELECT * FROM `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping` WHERE `active` = 1
    //     ';

    //     return \Db::getInstance()->executeS($sql);
    // }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        // $result = $this->cityFromDb();

        // $citys = array();

        // foreach ($result as $city) {
        //     $citys[$city['city_name']] = $city['id_thecoderpsshipping'];
        // }

        $builder
            ->add('thecoderpsshipping', EntityType::class, [
                'class' => Thecoderpsshipping::class,
                'query_builder' => function (ThecoderpsshippingRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.cityName', 'ASC');
                },
                'choice_label' => "city_name",
                'required' => true,
                'mapped' => false
            ])
            ->add('price', NumberType::class, [
                'label' => 'Price',
                'required' => true
            ])
            ->add('delivery_time', TextType::class, [
                'label' => 'Delivery information',
                'required' => true
            ])
            ->add('active', CheckboxType::class, [
                'required' => false
            ])
            ->add('save', SubmitType::class);
    }
}