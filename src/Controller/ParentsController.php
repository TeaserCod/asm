<?php

namespace App\Controller;

use App\Entity\Parents;
use App\Form\ParentsType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParentsController extends AbstractController
{
     /**
     * @Route("/parents", name = "parents_index")
     */
    public function parentsIndex(ManagerRegistry $doctrine) {
        $parents = $doctrine->getRepository(Parents::class)->findAll();
        return $this->render("parents/index.html.twig",
        [
            'parents' => $parents
        ]);
    }

    /**
     * @Route("/parents/detail/{id}", name = "parents_detail")
     */
    public function parentsDetail(ManagerRegistry $doctrine, $id) {
        $parents = $doctrine->getRepository(Parents::class)->find($id);
        if ($parents == null) {
            $this->addFlash("Error", "Parent not existed");
            return $this->redirectToRoute("parents_index");
        }
        return $this->render("parents/detail.html.twig",
        [
            'parents' => $parents
        ]);
    }

    /**
     * @Route("/parents/delete/{id}", name = "parents_delete")
     */
    public function parentsDelete(ManagerRegistry $doctrine, $id) {
        $parents = $doctrine->getRepository(Parents::class)->find($id);
        if ($parents == null) {
            $this->addFlash("Error", "Parent delete failed");
        } else {
            $manager = $doctrine->getManager();
            $manager->remove($parents);
            $manager->flush();
            $this->addFlash("Success", "Parent delete succeed");
        }
        return $this->redirectToRoute("parents_index");
    }

     /**
     * @Route("/parents/add", name = "parents_add")
     */
    public function parentsAdd(ManagerRegistry $doctrine, Request $request) {
        $parents = new Parents();
        $form = $this->createForm(ParentsType::class, $parents);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($parents);
            $manager->flush();

            $this->addFlash("Success", "Add Parent succeed");
            return $this->redirectToRoute("parents_index");
        }

        return $this->renderForm("parents/add.html.twig",
        [
            'parentsForm' => $form
        ]);
    }

    /**
     * @Route("/parents/edit/{id}", name = "parents_edit")
     */
    public function parentsEdit(ManagerRegistry $doctrine, Request $request, $id) {
        $parents = $doctrine->getRepository(Parents::class)->find($id);
        $form = $this->createForm(ParentsType::class, $parents);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($parents);
            $manager->flush();

            $this->addFlash("Success", "Edit Parent succeed");
            return $this->redirectToRoute("parents_index");
        }

        return $this->renderForm("parents/edit.html.twig",
        [
            'parentsForm' => $form
        ]);
    }
}
