<?php

/**
 * Project : everpsshippingperpostcode
 * @author Team Ever
 * @copyright Team Ever
 * @license   Tous droits réservés / Le droit d'auteur s'applique (All rights reserved / French copyright law applies)
 * @link https://www.team-ever.com
 */

use GraphQL\Utils\Value;

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


        Configuration::updateValue('THECODER1_ZONE_ID', $this->_installZone('Abidjan livraison'));
        Configuration::updateValue('THECODER2_ZONE_ID', $this->_installZone('Interrieur livraison'));

        $carrierConfig = array(
            'name' => $this->l('TheCoder Carrier'),
            'active' => true,
            'range_behavior' => 1,
            'need_range' => 1,
            'shipping_external' => true,
            'external_module_name' => $this->name,
            'shipping_method' => 2,
            'delay' => $this->l('Bla bla bla'),
        );

        $id_carrier = $this->addCarrier($carrierConfig);
        Configuration::updateValue('THECODER_ID', $id_carrier[0]);


        return parent::install()
            && $this->installSql()
            && $this->registerHook('actionFrontControllerSetMedia')
            && $this->registerHook('displayHeader')
            && $this->registerHook('footer')
            && $this->registerHook('displayCarrierExtraContent')
            && $this->registerHook('displayOrderConfirmation')
            && $this->registerHook('actionGetIDZoneByAddressID')
            && $this->registerHook('displayPDFInvoice')
            && $this->registerHook('additionalCustomerAddressFields')
            && $this->registerHook('actionAfterCreateAddressFormHandler')
            && $this->registerHook('actionValidateCustomerAddressForm')
            && $this->registerHook('actionObjectAddressAddBefore')
            && $this->registerHook('actionObjectAddressAddAfter')
            && $this->registerHook('actionObjectAddressUpdateAfter')
            && $this->registerHook('actionObjectAddressUpdateBefore')
            && $this->registerHook('actionObjectAddressDeleteAfter')
            && $this->registerHook('displayAdminOrder')
            && $this->registerHook('actionFrontControllerSetMedia')
            && $this->registerHook('updateCarrier');
    }



    public function uninstall()
    {
        $carrier = new Carrier(
            (int)Configuration::get('THECODER_ID')
        );
        $zone1 = new Zone(Configuration::get('THECODER1_ZONE_ID'));
        $zone2 = new Zone(Configuration::get('THECODER2_ZONE_ID'));

        Configuration::deleteByName('THECODER_ID');
        $carrier->delete();
        $zone1->delete();
        $zone2->delete();

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



        $sqlCart = '
        CREATE TABLE IF NOT EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping_cart`(
            `id_cart` INT(11) NOT NULL,
            `thecoderpsshipping_city_shipping` INT(11) NOT NULL,
            `price` decimal(20,6) NOT NULL DEFAULT "0.000000",
            PRIMARY KEY(
                `id_cart`
            )
        ) ENGINE = ' . pSQL(_MYSQL_ENGINE_) . ' DEFAULT CHARSET = utf8;
            ';

        return (Db::getInstance()->execute($sqlCity)
            && Db::getInstance()->execute($sqlCommune)
            && Db::getInstance()->execute($sqlCA)
            && Db::getInstance()->execute($sqlCityShipping)
            && Db::getInstance()->execute($sqlCart));
    }


    private function uninstallSql()
    {

        $sqlCity = 'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping`';
        $sqlCommune = 'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping_commune`';
        $sqlCA = 'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping_customer_address`';
        $sqlCityShipping = 'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping_city_shipping`';
        $sqlCart = 'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping_cart`';

        return (Db::getInstance()->execute($sqlCity)
            && Db::getInstance()->execute($sqlCommune)
            && Db::getInstance()->execute($sqlCA)
            && Db::getInstance()->execute($sqlCityShipping)
            && Db::getInstance()->execute($sqlCart));
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
            $id_zone_africa = Zone::getIdByName('Africa');
            $carrier->addZone($id_zone_africa ? $id_zone_africa : 1);

            return $carrier->id;
        }
        return false;
    }


    /**
     * Création de la zone clic and collect
     * @return bool
     */
    protected function _installZone($zone_name)
    {
        try {
            $zone = new Zone();
            $zone->name = $this->l($zone_name);
            $zone->save();
            return $zone->id;
        } catch (PrestaShopException $e) {
            return false;
        }
    }



    public function hookUpdateCarrier($params)
    {
        $id_carrier_old = (int) $params['id_carrier'];
        $id_carrier_new = (int) $params['carrier']->id;

        //for carrier
        if ($id_carrier_old === (int) Configuration::get('THECODER_ID')) {
            Configuration::updateValue('THECODER_ID', $id_carrier_new);
        }
    }

    public function hookDisplayCarrierExtraContent($params)
    {

        $repository = $this->get('thecoder.thecoderpsshipping.repository.thecoderpsshipping_repository');



        // if ($params['carrier']['id'] == Configuration::get('THECODER_ID')) {
        $this->smarty->assign(['cities' => $repository->findAll()]);
        return $this->display(__FILE__, 'extra_carrier.tpl');
        // }
    }

    // public function hookActionGetIDZoneByAddressID($params)
    // {
    //     $address = new Address($params['id_address']);

    //     //a call to  database for get all city of ivory coast
    //     //make a configuration for get this id by user
    //     $id_zone1 = Configuration::get('THECODER1_ZONE_ID');
    //     $id_zone2 = Configuration::get('THECODER2_ZONE_ID');


    //     if ($address->city == 'abidjan' && Country::getIdByName(1, $address->country) == 32) {
    //         return $id_zone1; //L'important est de retourner la zone ici
    //     } elseif ($address->city != 'abidjan' && Country::getIdByName(1, $address->country) == 32) {
    //         return $id_zone2; //L'important est de retourner la zone ici
    //     }
    // }

    //additionnal customer formfields
    public function hookAdditionalCustomerAddressFields($params)
    {
        //Get city from database
        $cities = \Db::getInstance()->executeS('
            SELECT * FROM `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping` WHERE `active` = 1
        ');

        $cityKey = array();
        $cityValue = array();

        //get all city for displaying
        foreach ($cities as $key => $value) {
            $cityKey[] = $value['id_thecoderpsshipping'];
            $cityValue[] = $value['city_name'];
        }


        //Combine city list information on one array
        $cityList = array_combine($cityKey, $cityValue);



        $formField = (new FormField)
            ->setName('id_thecoderpsshipping')
            ->setType('select')
            ->setAvailableValues($cityList)
            ->setLabel($this->getTranslator()->trans('City', [], 'Modules.Thecoderpsshipping.Front'));



        //if a city already choosed selected by default when user want update
        if (Tools::getIsset('id_address')) {
            $address = new Address(Tools::getValue('id_address'));

            if (!empty($cities)) {
                foreach ($cities as $city) {
                    $formField->addAvailableValue(
                        $city['id_thecoderpsshipping'],
                        $city['city_name']
                    );
                }
                if (!empty($address->id)) {
                    $id_thecoderpsshipping =  \Db::getInstance()->executeS('SELECT `id_thecoderpsshipping` FROM `' . _DB_PREFIX_ . 'thecoderpsshipping_customer_address` WHERE `id_address` = ' . $address->id);


                    $formField->setValue($id_thecoderpsshipping[0]['id_thecoderpsshipping']);
                    // $formField->setValue($id_thecoderpsshipping[0]['id_thecoderpsshipping']);

                }
            }
        }
        return array(
            $formField
        );
    }


    public function hookActionObjectAddressAddAfter($params)
    {

        if ($params['object']->id_thecoderpsshipping != null) {
            $db = \Db::getInstance();
            $result = $db->insert('thecoderpsshipping_customer_address', [
                'id_address' => (int) $params['object']->id,
                'id_thecoderpsshipping' => (int)$params['object']->id_thecoderpsshipping,
            ]);

            return $result;
        }
    }

    public function hookActionObjectAddressUpdateBefore($params)
    {




        if ($params['object']->id_thecoderpsshipping != null) {
            $db = \Db::getInstance();
            $result = $db->insert('thecoderpsshipping_customer_address', [
                'id_address' => (int) $params['object']->id,
                'id_thecoderpsshipping' => (int)$params['object']->id_thecoderpsshipping,
            ]);

            return $result;
        }
    }

    public function hookActionObjectAddressUpdateAfter($params)
    {
        if ($params['object']->id_thecoderpsshipping != null) {
            $db = \Db::getInstance();
            $result = $db->update('thecoderpsshipping_customer_address', [
                'id_thecoderpsshipping' => (int)$params['object']->id_thecoderpsshipping,
            ], 'id_address =' . (int) $params['object']->id, 1);

            return $result;
        }
    }

    public function hookActionObjectAddressDeleteAfter($params)
    {
        if ($params['object']->id_thecoderpsshipping != null) {
            $db = \Db::getInstance();
            $result = $db->delete('thecoderpsshipping_customer_address', 'id_address =' . (int) $params['object']->id);

            return $result;
        }
    }

    public function hookActionFrontControllerSetMedia()
    {
        $controller_name = Tools::getValue('controller');
        if ($controller_name == 'order') {
            $this->context->controller->registerJavascript(
                'thecoderpsshipping-javascript',
                $this->_path . '/views/js/order.js',
                [
                    'position' => 'top',
                    'priority' => 1000
                ]
            );
        }
        // $this->context->controller->registerCSS($this->_path . '/views/js/order.js');
    }
}