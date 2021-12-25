<?php

namespace App\Controller;

use App\Entity\Fee;
use App\Form\FeeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeeController extends AbstractController
{
     /**
     * @Route("/fees", name = "fees_index")
     */
    public function feeIndex(ManagerRegistry $doctrine) {
        $fees = $doctrine->getRepository(Fee::class)->findAll();
        return $this->render("fee/index.html.twig",
        [
            'fees' => $fees
        ]);
    }

    /**
     * @Route("/fees/detail/{id}", name = "fees_detail")
     */
    public function feesDetail(ManagerRegistry $doctrine, $id) {
        $fees = $doctrine->getRepository(Fee::class)->find($id);
        if ($fees == null) {
            $this->addFlash("Error", "Fee not existed");
            return $this->redirectToRoute("fees_index");
        }
        return $this->render("fee/detail.html.twig",
        [
            'fees' => $fees
        ]);
    }

    /**
     * @Route("/fees/delete/{id}", name = "fees_delete")
     */
    public function feesDelete(ManagerRegistry $doctrine, $id) {
        $fees = $doctrine->getRepository(Fee::class)->find($id);
        if ($fees == null) {
            $this->addFlash("Error", "Fee delete failed");
        } else {
            $manager = $doctrine->getManager();
            $manager->remove($fees);
            $manager->flush();
            $this->addFlash("Success", "Fee delete succeed");
        }
        return $this->redirectToRoute("fees_index");
    }

     /**
     * @Route("/fees/add", name = "fees_add")
     */
    public function feesAdd(ManagerRegistry $doctrine, Request $request) {
        $fees = new Fee();
        $form = $this->createForm(FeeType::class, $fees);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($fees);
            $manager->flush();

            $this->addFlash("Success", "Add Fee succeed");
            return $this->redirectToRoute("fees_index");
        }

        return $this->renderForm("fee/add.html.twig",
        [
            'feesForm' => $form
        ]);
    }

    /**
     * @Route("/fees/edit/{id}", name = "fees_edit")
     */
    public function feesEdit(ManagerRegistry $doctrine, Request $request, $id) {
        $fees = $doctrine->getRepository(Fee::class)->find($id);
        $form = $this->createForm(FeeType::class, $fees);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($fees);
            $manager->flush();

            $this->addFlash("Success", "Edit Fee succeed");
            return $this->redirectToRoute("fees_index");
        }

        return $this->renderForm("fee/edit.html.twig",
        [
            'feesForm' => $form
        ]);
    }
}
