<?php

/**
 * Project : everpsshippingperpostcode
 * @author Team Ever
 * @copyright Team Ever
 * @license   Tous droits réservés / Le droit d'auteur s'applique (All rights reserved / French copyright law applies)
 * @link https://www.team-ever.com
 */

if (!defined('_PS_VERSION_')) {
    exit;
}


class Thecoderpsshipping extends CarrierModule
{

    public function __construct()
    {
        $this->name = 'thecoderpsshipping';
        $this->tab = 'shipping';
        $this->version = '1.0.0';
        $this->author = 'Yenux TheCoder';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => '1.7.99',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TheCoder Shipping');
        $this->description = $this->l('Module for shipping.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }


    public function install()
    {


        $carrierConfig = array(
            0 => array(
                'name' => $this->l('Cerise Express Carrier'),
                'active' => true,
                'range_behavior' => 1,
                'need_range' => 1,
                'shipping_external' => true,
                'external_module_name' => $this->name,
                'shipping_method' => 2,
                'delay' => $this->l('24 houres')
            ),
            1 => array(
                'name' => $this->l('Cerise Standart Carrier'),
                'active' => true,
                'range_behavior' => 1,
                'need_range' => 1,
                'shipping_external' => true,
                'external_module_name' => $this->name,
                'shipping_method' => 2,
                'delay' => $this->l('48 houres')
            )
        );

        $id_carrier1 = $this->addCarrier($carrierConfig[0]);
        $id_carrier2 = $this->addCarrier($carrierConfig[1]);
        Configuration::updateValue('THECODER1_ID', $id_carrier1);
        Configuration::updateValue('THECODER2_ID', $id_carrier2);


        return parent::install()
            && $this->installSql()
            && $this->registerHook('displayCarrierExtraContent')
            && $this->registerHook('displayOrderConfirmation')
            && $this->registerHook('actionGetIDZoneByAddressID')
            && $this->registerHook('displayPDFInvoice')
            && $this->registerHook('updateCarrier');
    }



    public function uninstall()
    {
        $carrier1 = new Carrier(
            (int)Configuration::get('THECODER1_ID')
        );
        $carrier2 = new Carrier(
            (int)Configuration::get('THECODER2_ID')
        );

        Configuration::deleteByName('THECODER1_ID');
        Configuration::deleteByName('THECODER2_ID');
        $carrier1->delete();
        $carrier2->delete();

        return parent::uninstall() && $this->uninstallSql();
    }

    private function installSql()
    {

        $sqlCity = '
        CREATE TABLE IF NOT EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping` (
        `id_thecoderpsshipping` INT AUTO_INCREMENT NOT NULL,
        `id_country` INT NOT NULL,
        `city_name` VARCHAR(64) NOT NULL,
        `active` TINYINT(1) NOT NULL,
        PRIMARY KEY(`id_thecoderpsshipping`, `id_country`)) 
        ENGINE = ' . pSQL(_MYSQL_ENGINE_) . ' DEFAULT CHARSET=utf8;
        ';

        $sqlCommune = '
        CREATE TABLE IF NOT EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping_commune`(
            `id_thecoderpsshipping_commune` INT AUTO_INCREMENT NOT NULL,
            `commune_name` VARCHAR(64) NOT NULL,
            `active` TINYINT(1) NOT NULL,
            PRIMARY KEY(`id_thecoderpsshipping_commune`)
        ) ENGINE = ' . pSQL(_MYSQL_ENGINE_) . ' DEFAULT CHARSET = utf8;
        ';

        $sqlCA = '
        CREATE TABLE IF NOT EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping_customer_address`(
            `id_thecoderpsshipping_customer_address` INT AUTO_INCREMENT NOT NULL,
            `id_address` INT DEFAULT NULL,
            `id_thecoderpsshipping` INT DEFAULT NULL,
            PRIMARY KEY(
                `id_thecoderpsshipping_customer_address`, `id_address`, `id_thecoderpsshipping`
            )
        ) ENGINE = ' . pSQL(_MYSQL_ENGINE_) . ' DEFAULT CHARSET = utf8;
            ';

        $sqlCityShipping = '
        CREATE TABLE IF NOT EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping_city_shipping`(
            `id_thecoderpsshipping_city_shipping` INT AUTO_INCREMENT NOT NULL,
            `id_thecoderpsshipping` INT DEFAULT NULL,
            `price` decimal(20,6) NOT NULL DEFAULT "0.000000",
            `active` TINYINT(1) NOT NULL,
            PRIMARY KEY(
                `id_thecoderpsshipping_city_shipping`, `id_thecoderpsshipping`
            )
        ) ENGINE = ' . pSQL(_MYSQL_ENGINE_) . ' DEFAULT CHARSET = utf8;
            ';


        return (Db::getInstance()->execute($sqlCity)
            && Db::getInstance()->execute($sqlCommune)
            && Db::getInstance()->execute($sqlCA)
            && Db::getInstance()->execute($sqlCityShipping));
    }


    private function uninstallSql()
    {

        $sqlCity = 'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping`';
        $sqlCommune = 'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping_commune`';
        $sqlCA = 'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping_customer_address`';
        $sqlCityShipping = 'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping_city_shipping`';

        return (Db::getInstance()->execute($sqlCity)
            && Db::getInstance()->execute($sqlCommune)
            && Db::getInstance()->execute($sqlCA)
            && Db::getInstance()->execute($sqlCityShipping));
    }


    public function getOrderShippingCost($params, $shipping_cost)
    {
        return (float)$shipping_cost;
    }

    public function getOrderShippingCostExternal($params)
    {
        return 0.00;
    }

    protected function addCarrier($config)
    {
        $carrier = new Carrier();
        $carrier->name = $config['name'];
        $carrier->is_module = true;
        $carrier->active = $config['active'];
        $carrier->range_behavior = $config['range_behavior'];
        $carrier->need_range = $config['need_range'];
        $carrier->shipping_external = $config['shipping_external'];
        $carrier->range_behavior = $config['range_behavior'];
        $carrier->external_module_name = $config['external_module_name'];
        $carrier->shipping_method = $config['shipping_method'];

        foreach (Language::getLanguages() as $lang) {
            $carrier->delay[$lang['id_lang']] = $config['delay'];
        }

        if ($carrier->add() == true) {
            Configuration::updateValue('THECODER_ID', (int)$carrier->id);

            //Add carrier groups
            $groups_ids = array();

            $groups = Group::getGroups(Context::getContext()->language->id);
            foreach ($groups as $group) {
                $groups_ids[] = $group['id_group'];
            }

            $carrier->setGroups($groups_ids);


            //Add carrier Range
            $range_price = new RangePrice();
            $range_price->id_carrier = $carrier->id;
            $range_price->delimiter1 = '0';
            $range_price->delimiter2 = '10000';
            $range_price->add();

            //Add carrier zone
            $id_zone_afrique = Zone::getIdByName('Africa');

            $carrier->addZone($id_zone_afrique ? $id_zone_afrique : 1);

            return $carrier->id;
        }
        return false;
    }


    public function hookUpdateCarrier($params)
    {
        $id_carrier_old = (int) $params['id_carrier'];
        $id_carrier_new = (int) $params['carrier']->id;

        //for carrier 1
        if ($id_carrier_old === (int) Configuration::get('THECODER1_ID')) {
            Configuration::updateValue('THECODER1_ID', $id_carrier_new);
        }

        //for carrier 2
        if ($id_carrier_old === (int) Configuration::get('THECODER2_ID')) {
            Configuration::updateValue('THECODER2_ID', $id_carrier_new);
        }
    }

    public function hookDisplayCarrierExtraContent($params)
    {
        if ($params['carrier']['id'] == Configuration::get('THECODER1_ID')) {
            return $this->display(__FILE__, 'extra_carrier1.tpl');
        } else {
            return $this->display(__FILE__, 'extra_carrier2.tpl');
        }
    }

    public function hookActionGetIDZoneByAddressID($params)
    {
        $address = new Address($params['id_address']);

        //make a configuration for get this id by user
        $id_zone = 9;

        // dump($address->city);
        // die();
        if ($address->city == 'abidjan') {
            return $id_zone; //L'important est de retourner la zone ici
        }
    }
}