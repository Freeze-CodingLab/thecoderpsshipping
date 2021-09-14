<?php
// modules/your-module/src/Controller/DemoController.php

namespace Thecoderpsshipping\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Thecoderpsshipping\Form\CommuneType;
use Thecoderpsshipping\Entity\ThecoderpsshippingCommune;

class CommuneController extends FrameworkBundleAdminController
{
    public function addAction(Request $request): Response
    {
        $commune = new ThecoderpsshippingCommune();

        $form = $this->createForm(CommuneType::class, $commune);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commune = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commune);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'city added!'
            );
            $this->redirectToRoute('thecoder_commune_list', array(), 301);
        }

        return $this->render(
            '@Modules/thecoderpsshipping/views/templates/admin/commune/add_commune.html.twig',
            [
                'form' => $form->createView(),
                'commune' => $commune,
            ]
        );
    }

    public function listCommune()
    {

        $communes = $this->getDoctrine()
            ->getRepository(ThecoderpsshippingCommune::class)
            ->findAll();


        return $this->render(
            '@Modules/thecoderpsshipping/views/templates/admin/commune/list_commune.html.twig',
            [
                'communes' => $communes
            ]
        );
    }

    public function updateAction($id,  Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $communeForUpdate = $em->getRepository(ThecoderpsshippingCommune::class)->find($id);
        $form = $this->createForm(CommuneType::class, $communeForUpdate);

        $form->handleRequest($request);

        if (!$communeForUpdate) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        if (
            $form->isSubmitted() &&
            $form->isValid()
        ) {


            $communeForUpdate->setcommuneName($form->get('commune_name')->getData());
            $communeForUpdate->setActive($form->get('active')->getData());

            $em->flush();

            $this->addFlash(
                'notice',
                'Commune updated!'
            );
            return $this->redirectToRoute('thecoder_commune_list', array(), 301);
        }


        return $this->render(
            '@Modules/thecoderpsshipping/views/templates/admin/commune/edit_commune.html.twig',
            [
                'form' => $form->createView(),
                'commune' => $communeForUpdate
            ]
        );
    }

    public function deleteAction($id,  Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $commune = $em->getRepository(ThecoderpsshippingCommune::class)->find($id);


        if (!$commune) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $em->remove($commune);
        $em->flush();

        $this->addFlash(
            'notice',
            'commune deleted!'
        );
        return $this->redirectToRoute('thecoder_commune_list', array(), 301);
    }
}