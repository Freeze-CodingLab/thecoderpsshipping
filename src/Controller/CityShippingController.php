<?php
// modules/your-module/src/Controller/DemoController.php

namespace Thecoderpsshipping\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Thecoderpsshipping\Form\ShippingType;
use Thecoderpsshipping\Entity\ThecoderpsshippingCityShipping;

class CityShippingController extends FrameworkBundleAdminController
{
    public function addAction(Request $request): Response
    {
        $cityShipping = new ThecoderpsshippingCityShipping();

        $form = $this->createForm(ShippingType::class, $cityShipping);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cityShipping = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cityShipping);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'city added!'
            );
            // $this->redirectToRoute('thecoder_commune_list', array(), 301);
        }

        return $this->render(
            '@Modules/thecoderpsshipping/views/templates/admin/shipping/add_shipping.html.twig',
            [
                'form' => $form->createView(),
                'cityShipping' => $cityShipping,
            ]
        );
    }

    public function listShipping()
    {

        $shippings = $this->getDoctrine()
            ->getRepository(ThecoderpsshippingCityShipping::class)
            ->findAll();


        return $this->render(
            '@Modules/thecoderpsshipping/views/templates/admin/shipping/list.html.twig',
            [
                'shippings' => $shippings
            ]
        );
    }
}