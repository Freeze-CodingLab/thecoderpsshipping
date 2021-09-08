<?php
// modules/your-module/src/Controller/DemoController.php

namespace Thecoderpsshipping\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Thecoderpsshipping\Form\CityType;
use Thecoderpsshipping\Entity\Thecoderpsshipping;

class ThecoderpsshippingController extends FrameworkBundleAdminController
{

    public function demoAction()
    {
        return $this->render('@Modules/thecoderpsshipping/views/templates/admin/demo.html.twig');
    }

    public function addAction(Request $request): Response
    {
        $city = new Thecoderpsshipping();

        $form = $this->createForm(CityType::class, $city);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $city = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($city);
            $entityManager->flush();
        }

        return $this->render(
            '@Modules/thecoderpsshipping/views/templates/admin/add_city.html.twig',
            [
                'form' => $form->createView(),
                'city' => $city,
            ]
        );
    }
}