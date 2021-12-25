<?php

namespace App\DataFixtures;

use App\Entity\Lecture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LectureFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i<=15; $i++) {
            $book = new Lecture;
            $book->setName("Lecture $i");
            $book->setAddress("Hanoi");
            $book->setPicture("cover.jpg");
            $book->setGender("Female");
            $manager->persist($book);
        }

        $manager->flush();
    }
}
