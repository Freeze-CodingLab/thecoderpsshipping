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
            $entityManager = $this->getDoctrine()->getManager();

            $cityShipping->setThecoderpsshipping($form->get('thecoderpsshipping')->getData());
            $cityShipping->setPrice($form->get('price')->getData());
            $cityShipping->setDeliveryTime($form->get('delivery_time')->getData());
            $cityShipping->setActive($form->get('active')->getData());

            $entityManager->persist($cityShipping);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'City information added!'
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