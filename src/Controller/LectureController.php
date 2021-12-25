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
           //code xử lý việc upload ảnh
           //B1: lấy ảnh từ file upload
           $image = $lectures->getPicture();
           //B2: đặt tên mới cho ảnh => đảm bảo mỗi ảnh sẽ có 1 tên duy nhất
           $imgName = uniqid(); //unique id
           //B3: lấy đuôi ảnh (image extension)
           $imgExtension = $image->guessExtension();
           //Note: cần edit code lại trong file Book Entity (Book.php)
           //B4: nối tên mới & đuôi ảnh thành tên hoàn chỉnh để lưu vào DB & thư mục
           $imageName = $imgName . "." . $imgExtension;
           //B5: di chuyển ảnh vào thư mục chỉ định
           try {
             $image->move(
                 $this->getParameter('lecture_picture'), $imageName
                 /* Note: cần khai báo đường dẫn thư mục chứa ảnh
                 ở file config/services.yaml */
             );  
           } catch (FileException $e) {
               throwException($e);
           }
           //B6: lưu tên ảnh vào DB
           $lectures->setPicture($imageName);
       
            //đẩy dữ liệu từ form vào DB
            $manager = $doctrine->getManager();
            $manager->persist($lectures);
            $manager->flush();

            //hiển thị thông báo và redirect về trang book index
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
           //code xử lý việc upload ảnh
           //B1: lấy ảnh từ file upload
           $file = $form['picture']->getData();
            //B2: check xem ảnh có null không
            if ($file != null) {
           $image = $lectures->getPicture();
           //B2: đặt tên mới cho ảnh => đảm bảo mỗi ảnh sẽ có 1 tên duy nhất
           $imgName = uniqid(); //unique id
           //B3: lấy đuôi ảnh (image extension)
           $imgExtension = $image->guessExtension();
           //Note: cần edit code lại trong file Book Entity (Book.php)
           //B4: nối tên mới & đuôi ảnh thành tên hoàn chỉnh để lưu vào DB & thư mục
           $imageName = $imgName . "." . $imgExtension;
           //B5: di chuyển ảnh vào thư mục chỉ định
           try {
             $image->move($this->getParameter('lecture_picture'), $imageName
                 /* Note: cần khai báo đường dẫn thư mục chứa ảnh
                 ở file config/services.yaml */
             );  
           } catch (FileException $e) {
               throwException($e);
           }
           //B6: lưu tên ảnh vào DB
           $lectures->setPicture($imageName);
        }
       
            //đẩy dữ liệu từ form vào DB
            $manager = $doctrine->getManager();
            $manager->persist($lectures);
            $manager->flush();

            //hiển thị thông báo và redirect về trang book index
            $this->addFlash("Success", "Add Lecture succeed");
            return $this->redirectToRoute("lectures_index");
        }

        return $this->renderForm("lecture/add.html.twig",
        [
            'lecturesForm' => $form
        ]);
    }
}
