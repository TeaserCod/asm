<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    /**
     * @Route("/students", name = "students_index")
     */
    public function studentsIndex(ManagerRegistry $doctrine) {
        $students = $doctrine->getRepository(Student::class)->findAll();
        return $this->render("student/index.html.twig",
        [
            'students' => $students
        ]);
    }

    /**
     * @Route("/students/detail/{id}", name = "students_detail")
     */
    public function studentsDetail(ManagerRegistry $doctrine, $id) {
        $students = $doctrine->getRepository(Student::class)->find($id);
        if ($students == null) {
            $this->addFlash("Error", "Student not existed");
            return $this->redirectToRoute("students_index");
        }
        return $this->render("student/detail.html.twig",
        [
            'students' => $students
        ]);
    }

    /**
     * @Route("/students/delete/{id}", name = "students_delete")
     */
    public function studentsDelete(ManagerRegistry $doctrine, $id) {
        $students = $doctrine->getRepository(Student::class)->find($id);
        if ($students == null) {
            $this->addFlash("Error", "Student delete failed");
        } else {
            $manager = $doctrine->getManager();
            $manager->remove($students);
            $manager->flush();
            $this->addFlash("Success", "Student delete succeed");
        }
        return $this->redirectToRoute("students_index");
    }

     /**
     * @Route("/students/add", name = "students_add")
     */
    public function studentsAdd(ManagerRegistry $doctrine, Request $request) {
        $students = new Student();
        $form = $this->createForm(StudentType::class, $students);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($students);
            $manager->flush();

            $this->addFlash("Success", "Add Student succeed");
            return $this->redirectToRoute("students_index");
        }

        return $this->renderForm("student/add.html.twig",
        [
            'studentsForm' => $form
        ]);
    }

    /**
     * @Route("/students/edit/{id}", name = "students_edit")
     */
    public function studentsEdit(ManagerRegistry $doctrine, Request $request, $id) {
        $students = $doctrine->getRepository(Student::class)->find($id);
        $form = $this->createForm(StudentType::class, $students);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($students);
            $manager->flush();

            $this->addFlash("Success", "Edit Student succeed");
            return $this->redirectToRoute("students_index");
        }

        return $this->renderForm("student/edit.html.twig",
        [
            'studentsForm' => $form
        ]);
    }
}
