<?php

namespace App\Controller;

use App\Entity\Sclass;
use App\Form\SclassType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SclassController extends AbstractController
{
   /**
     * @Route("/class", name = "class_index")
     */
    public function sclassIndex(ManagerRegistry $doctrine) {
        $sclass = $doctrine->getRepository(Sclass::class)->findAll();
        return $this->render("sclass/index.html.twig",
        [
            'sclass' => $sclass
        ]);
    }

    /**
     * @Route("/class/detail/{id}", name = "class_detail")
     */
    public function sclassDetail(ManagerRegistry $doctrine, $id) {
        $sclass = $doctrine->getRepository(Sclass::class)->find($id);
        if ($sclass == null) {
            $this->addFlash("Error", "Class not existed");
            return $this->redirectToRoute("class_index");
        }
        return $this->render("sclass/detail.html.twig",
        [
            'sclass' => $sclass
        ]);
    }

    /**
     * @Route("/class/delete/{id}", name = "class_delete")
     */
    public function sclassDelete(ManagerRegistry $doctrine, $id) {
        $sclass = $doctrine->getRepository(Sclass::class)->find($id);
        if ($sclass == null) {
            $this->addFlash("Error", "Class delete failed");
        } else {
            $manager = $doctrine->getManager();
            $manager->remove($sclass);
            $manager->flush();
            $this->addFlash("Success", "Class delete succeed");
        }
        return $this->redirectToRoute("class_index");
    }

     /**
     * @Route("/class/add", name = "class_add")
     */
    public function sclassAdd(ManagerRegistry $doctrine, Request $request) {
        $sclass = new Sclass();
        $form = $this->createForm(SclassType::class, $sclass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($sclass);
            $manager->flush();

            $this->addFlash("Success", "Add Class succeed");
            return $this->redirectToRoute("class_index");
        }

        return $this->renderForm("sclass/add.html.twig",
        [
            'sclassForm' => $form
        ]);
    }

    /**
     * @Route("/class/edit/{id}", name = "class_edit")
     */
    public function sclassEdit(ManagerRegistry $doctrine, Request $request, $id) {
        $sclass = $doctrine->getRepository(Sclass::class)->find($id);
        $form = $this->createForm(SclassType::class, $sclass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($sclass);
            $manager->flush();

            $this->addFlash("Success", "Edit Class succeed");
            return $this->redirectToRoute("fees_index");
        }

        return $this->renderForm("sclass/edit.html.twig",
        [
            'sclassForm' => $form
        ]);
    }
}
