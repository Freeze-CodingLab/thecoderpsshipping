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


            $this->addFlash(
                'notice',
                'commune added!'
            );
        }

        return $this->render(
            '@Modules/thecoderpsshipping/views/templates/admin/city/add_city.html.twig',
            [
                'form' => $form->createView(),
                'city' => $city,
            ]
        );
    }

    public function listCity()
    {

        $citys = $this->getDoctrine()
            ->getRepository(Thecoderpsshipping::class)
            ->findAll();


        return $this->render(
            '@Modules/thecoderpsshipping/views/templates/admin/city/list_city.html.twig',
            [
                'citys' => $citys
            ]
        );
    }

    public function updateAction($id,  Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cityUpdate = $em->getRepository(Thecoderpsshipping::class)->find($id);
        $form = $this->createForm(CityType::class, $cityUpdate);

        $form->handleRequest($request);

        if (!$cityUpdate) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        if (
            $form->isSubmitted() &&
            $form->isValid()
        ) {


            $cityUpdate->setCityName($form->get('city_name')->getData());
            $cityUpdate->setActive($form->get('active')->getData());

            $em->flush();

            $this->addFlash(
                'notice',
                'City updated!'
            );
            return $this->redirectToRoute('thecoder_list_city', array(), 301);
        }


        return $this->render(
            '@Modules/thecoderpsshipping/views/templates/admin/city/edit_city.html.twig',
            [
                'form' => $form->createView(),
                'city' => $cityUpdate
            ]
        );
    }


    public function deleteAction($id,  Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository(Thecoderpsshipping::class)->find($id);


        if (!$city) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $em->remove($city);
        $em->flush();

        $this->addFlash(
            'notice',
            'City updated!'
        );
        return $this->redirectToRoute('thecoder_city_list', array(), 301);
    }
}