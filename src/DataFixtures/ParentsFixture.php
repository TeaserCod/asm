<?php

namespace App\DataFixtures;

use App\Entity\Parents;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ParentsFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i<=10; $i++) {
            $author = new Parents;
            $author->setName("Author $i");
            $author->setGender("Male");
            $author->setPhonenumber(rand(350000000, 999999999));
            $manager->persist($author);
        }
        $manager->flush();
    }
}
