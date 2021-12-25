<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    /**
     * @Route("/courses", name = "courses_index")
     */
    public function coursesIndex(ManagerRegistry $doctrine) {
        $courses = $doctrine->getRepository(Course::class)->findAll();
        return $this->render("course/index.html.twig",
        [
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/courses/detail/{id}", name = "courses_detail")
     */
    public function coursesDetail(ManagerRegistry $doctrine, $id) {
        $courses = $doctrine->getRepository(Course::class)->find($id);
        if ($courses == null) {
            $this->addFlash("Error", "Course not existed");
            return $this->redirectToRoute("courses_index");
        }
        return $this->render("course/detail.html.twig",
        [
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/courses/delete/{id}", name = "courses_delete")
     */
    public function coursesDelete(ManagerRegistry $doctrine, $id) {
        $courses = $doctrine->getRepository(Course::class)->find($id);
        if ($courses == null) {
            $this->addFlash("Error", "Course delete failed");
        } else {
            $manager = $doctrine->getManager();
            $manager->remove($courses);
            $manager->flush();
            $this->addFlash("Success", "Course delete succeed");
        }
        return $this->redirectToRoute("courses_index");
    }

     /**
     * @Route("/courses/add", name = "courses_add")
     */
    public function coursesAdd(ManagerRegistry $doctrine, Request $request) {
        $courses = new Course();
        $form = $this->createForm(CourseType::class, $courses);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($courses);
            $manager->flush();

            $this->addFlash("Success", "Add Course succeed");
            return $this->redirectToRoute("courses_index");
        }

        return $this->renderForm("course/add.html.twig",
        [
            'coursesForm' => $form
        ]);
    }

    /**
     * @Route("/courses/edit/{id}", name = "courses_edit")
     */
    public function coursesEdit(ManagerRegistry $doctrine, Request $request, $id) {
        $courses = $doctrine->getRepository(Course::class)->find($id);
        $form = $this->createForm(CourseType::class, $courses);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($courses);
            $manager->flush();

            $this->addFlash("Success", "Edit Course succeed");
            return $this->redirectToRoute("courses_index");
        }

        return $this->renderForm("course/edit.html.twig",
        [
            'coursesForm' => $form
        ]);
    }
}
