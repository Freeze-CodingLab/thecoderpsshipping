<?php
// modules/your-module/src/Controller/DemoController.php

namespace Thecoderpsshipping\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class ThecoderpsshippingController extends FrameworkBundleAdminController
{

    public function demoAction()
    {
        return $this->render('@Modules/thecoderpsshipping/views/templates/admin/demo.html.twig');
    }

    public function cityAddAction()
    {
        return $this->render('@Modules/thecoderpsshipping/views/templates/admin/add_city.html.twig')
    }
}
