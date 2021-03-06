<?php

namespace App\Controller;

use App\Entity\Lecture;
use App\Form\LectureType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\throwException;

class LectureController extends AbstractController
{
    /**
    * @Route("/lectures", name="lectures_index")
    */
    public function lecturesIndex(ManagerRegistry $doctrine) {
        $lectures = $doctrine->getRepository(Lecture::class)->findAll();
        return $this->render("lecture/index.html.twig",
        [
            'lectures' => $lectures
        ]);
    }

    /**
     * @Route("/lectures/detail/{id}", name="lectures_detail")
     */
    public function lecturesDetail(ManagerRegistry $doctrine, $id) {
        $lectures = $doctrine->getRepository(Lecture::class)->find($id);
        if ($lectures == null) {
            $this->addFlash("Error", "Lecture not exist");
            return $this->redirectToRoute("lectures_index");
        }
        return $this->render("lecture/detail.html.twig",
        [
            'lectures' => $lectures
        ]);
    }

    /** 
     * @Route("/lectures/delete/{id}", name="lectures_delete")
     */
    public function lecturesDelete(ManagerRegistry $doctrine, $id) {
        $lectures = $doctrine->getRepository(Lecture::class)->find($id);
        if ($lectures == null) {
            $this->addFlash("Error", "Lecture delete failed");  
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($lectures);
            $manager->flush();
            $this->addFlash("Success", "Lecture delete succeed"); 
        }
        return $this->redirectToRoute("lectures_index");
    }

    /**
     * @Route("/lectures/add", name="lectures_add")
     */
    public function lecturesAdd(ManagerRegistry $doctrine, Request $request) {
        $lectures = new Lecture();
        $form = $this->createForm(LectureType::class, $lectures);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           //code x??? l?? vi???c upload ???nh
           //B1: l???y ???nh t??? file upload
           $image = $lectures->getPicture();
           //B2: ?????t t??n m???i cho ???nh => ?????m b???o m???i ???nh s??? c?? 1 t??n duy nh???t
           $imgName = uniqid(); //unique id
           //B3: l???y ??u??i ???nh (image extension)
           $imgExtension = $image->guessExtension();
           //Note: c???n edit code l???i trong file Book Entity (Book.php)
           //B4: n???i t??n m???i & ??u??i ???nh th??nh t??n ho??n ch???nh ????? l??u v??o DB & th?? m???c
           $imageName = $imgName . "." . $imgExtension;
           //B5: di chuy???n ???nh v??o th?? m???c ch??? ?????nh
           try {
             $image->move(
                 $this->getParameter('lecture_picture'), $imageName
                 /* Note: c???n khai b??o ???????ng d???n th?? m???c ch???a ???nh
                 ??? file config/services.yaml */
             );  
           } catch (FileException $e) {
               throwException($e);
           }
           //B6: l??u t??n ???nh v??o DB
           $lectures->setPicture($imageName);
       
            //?????y d??? li???u t??? form v??o DB
            $manager = $doctrine->getManager();
            $manager->persist($lectures);
            $manager->flush();

            //hi???n th??? th??ng b??o v?? redirect v??? trang book index
            $this->addFlash("Success", "Add Lecture succeed");
            return $this->redirectToRoute("lectures_index");
        }

        return $this->renderForm("lecture/add.html.twig",
        [
            'lecturesForm' => $form
        ]);
    }

    /**
     * @Route("/lectures/edit/{id}", name="lectures_edit")
     */
    public function lecturesEdit(ManagerRegistry $doctrine, Request $request, $id) {
        $lectures = new Lecture();
        $form = $this->createForm(LectureType::class, $lectures);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           //code x??? l?? vi???c upload ???nh
           //B1: l???y ???nh t??? file upload
           $file = $form['picture']->getData();
            //B2: check xem ???nh c?? null kh??ng
            if ($file != null) {
           $image = $lectures->getPicture();
           //B2: ?????t t??n m???i cho ???nh => ?????m b???o m???i ???nh s??? c?? 1 t??n duy nh???t
           $imgName = uniqid(); //unique id
           //B3: l???y ??u??i ???nh (image extension)
           $imgExtension = $image->guessExtension();
           //Note: c???n edit code l???i trong file Book Entity (Book.php)
           //B4: n???i t??n m???i & ??u??i ???nh th??nh t??n ho??n ch???nh ????? l??u v??o DB & th?? m???c
           $imageName = $imgName . "." . $imgExtension;
           //B5: di chuy???n ???nh v??o th?? m???c ch??? ?????nh
           try {
             $image->move($this->getParameter('lecture_picture'), $imageName
                 /* Note: c???n khai b??o ???????ng d???n th?? m???c ch???a ???nh
                 ??? file config/services.yaml */
             );  
           } catch (FileException $e) {
               throwException($e);
           }
           //B6: l??u t??n ???nh v??o DB
           $lectures->setPicture($imageName);
        }
       
            //?????y d??? li???u t??? form v??o DB
            $manager = $doctrine->getManager();
            $manager->persist($lectures);
            $manager->flush();

            //hi???n th??? th??ng b??o v?? redirect v??? trang book index
            $this->addFlash("Success", "Add Lecture succeed");
            return $this->redirectToRoute("lectures_index");
        }

        return $this->renderForm("lecture/add.html.twig",
        [
            'lecturesForm' => $form
        ]);
    }
}
