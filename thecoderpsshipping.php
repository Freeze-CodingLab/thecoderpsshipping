<?php

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
        return parent::install()
            && $this->registerHook('displayCarrierExtraContent')
            && $this->registerHook('displayOrderConfirmation')
            && $this->registerHook('displayPDFInvoice')
            && $this->registerHook('updateCarrier');
    }


    public function uninstall()
    {
        return parent::uninstall();
    }

    public function getOrderShippingCost($params, $shipping_cost)
    {
    }

    public function getOrderShippingCostExternal($params)
    {
    }
}