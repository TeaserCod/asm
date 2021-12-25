<?php

namespace App\DataFixtures;

use App\Entity\Fee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FeeFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i<=15; $i++) {
            $book = new Fee;
            $book->setName("hocphi $i");
            $book->setCostfee(9999.99);
            $manager->persist($book);
        }

        $manager->flush();
    }
}
