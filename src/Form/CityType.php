<?php

// src/AppBundle/Form/TaskType.php
namespace Thecoderpsshipping\Form;

use Context;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CityType extends AbstractType
{


    private function countryFormDb()
    {
        $sql = '
        SELECT * FROM `' . pSQL(_DB_PREFIX_) . 'country` WHERE `active` = 1
        ';

        return \Db::getInstance()->executeS($sql);
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $result = $this->countryFormDb();

        $citys = array();

        foreach ($result as $city) {
            if ($city['id_country'] == 32) {
                $citys[\Country::getNameById(1, $city['id_country'])] = $city['id_country'];
            }
        }




        $builder
            ->add('country_id', ChoiceType::class, [
                'label' => 'Country',
                'required' => true,
                'choices' => $citys,
                'attr' => ['readonly' => true]
            ])
            ->add('city_name', TextType::class, [
                'label' => 'City name',
                'required' => true
            ])
            ->add('active', CheckboxType::class, [
                'required' => false
            ])
            ->add('save', SubmitType::class);
    }
}