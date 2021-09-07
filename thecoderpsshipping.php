<?php



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
        if (extension_loaded('curl') == false) {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');
            return false;
        }

        $carrier = $this->addCarrier();
        $this->addZones($carrier);
        $this->addGroups($carrier);
        $this->addRanges($carrier);

        return parent::install()
            && $this->installSql()
            && $this->registerHook('displayCarrierExtraContent')
            && $this->registerHook('displayOrderConfirmation')
            && $this->registerHook('displayPDFInvoice')
            && $this->registerHook('updateCarrier');
    }

    protected function installSql()
    {
        $sql = [];
        $sql[]= '
        CREATE TABLE `'. pSQL('_DB_PREFIX_') .'thecoderpsshipping` (
            `id_thecoderpsshipping` INT AUTO_INCREMENT NOT NULL,
            `id_country` INT NOT NULL,
            `city_name` VARCHAR(64) NOT NULL,
            `active` TINYINT(1) NOT NULL,
            PRIMARY KEY(`id_thecoderpsshipping`)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = '.pSQL('_MYSQL_ENGINE_').';
        ';

        $sql[]= '
        CREATE TABLE `'. pSQL('_PS_PREFIX_') .'thecoderpsshipping_commune` (
            `id_thecoderpsshipping_commune` INT AUTO_INCREMENT NOT NULL,
            `commune_name` VARCHAR(64) NOT NULL,
            `active` TINYINT(1) NOT NULL,
            PRIMARY KEY(`id_thecoderpsshipping_commune`)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = '. pSQL('_MYSQL_ENGINE_').';
        ';

        $sql[] = '
            CREATE TABLE IF NOT EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping_customer_address` (
            `id_thecoderpsshipping_customer_address` INT AUTO_INCREMENT NOT NULL,
            `id_address` INT DEFAULT NULL,
            `id_thecoderpsshipping` INT DEFAULT NULL,
            PRIMARY KEY(id_thecoderpsshipping_customer_address))
            DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE =' . pSQL(_MYSQL_ENGINE_) . ';
            ';


        foreach ($sql as $query) {
            if (DB::getInstance()->execute($query) == false) {
                return false;
            } else {
                return true;
            }
        }

    }

    public function uninstall()
    {
        $carrier = new Carrier(
            (int)Configuration::get('THECODER_ID')
        );

        Configuration::deleteByName('THECODER_ID');
        $carrier->delete();

        return parent::uninstall() && $this->uninstallSql();
    }


    protected function uninstallSql()
    {

    $sql = array();

        $sql[] = 'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping`';
        $sql[] = 'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping_commune`';
        $sql[] = 'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'thecoderpsshipping_customer_address`';

        foreach ($sql as $query) {
            if (Db::getInstance()->execute($query) == false) {
                return false;
            } else {
                return true;
            }
        }

    }


    public function getOrderShippingCost($params, $shipping_cost)
    {
    }

    public function getOrderShippingCostExternal($params)
    {
    }

    protected function addCarrier()
    {
        $carrier = new Carrier();
        $carrier->name = $this->l('Delivery by') . ' ' . Configuration::get('PS_SHOP_NAME');
        $carrier->is_module = true;
        $carrier->active = 1;
        $carrier->range_behavior = 1;
        $carrier->need_range = 1;
        $carrier->shipping_external = true;
        $carrier->range_behavior = 0;
        $carrier->external_module_name = $this->name;
        $carrier->shipping_method = 2;

        foreach (Language::getLanguages() as $lang) {
            $carrier->delay[$lang['id_lang']] = $this->l('Pick a shipping');
        }

        if ($carrier->add() == true) {
            Configuration::updateValue('THECODER_ID', (int)$carrier->id);
            return $carrier;
        }
        return false;
    }

    protected function addZones($carrier)
    {
        $zones = Zone::getZones();

        foreach ($zones as $zone) {
            $carrier->addZone($zone['id_zone']);
        }
    }

    protected function addGroups($carrier)
    {
        $groups_ids = array();

        $groups = Group::getGroups(Context::getContext()->language->id);
        foreach ($groups as $group) {
            $groups_ids[] = $group['id_group'];
        }

        $carrier->setGroups($groups_ids);
    }

    protected function addRanges($carrier)
    {
        $range_price = new RangePrice();
        $range_price->id_carrier = $carrier->id;
        $range_price->delimiter1 = '0';
        $range_price->delimiter2 = '10000';
        $range_price->add();
    }

    public function hookUpdateCarrier($params)
    {
        $id_carrier_old = (int) $params['id_carrier'];
        $id_carrier_new = (int) $params['carrier']->id;
        if ($id_carrier_old === (int) Configuration::get('THECODER_ID')) {
            Configuration::updateValue('THECODER_ID', $id_carrier_new);
        }
    }

    public function hookDisplayCarrierExtraContent($params)
    {
        return $this->display(__FILE__, 'extra_carrier.tpl');
    }

}