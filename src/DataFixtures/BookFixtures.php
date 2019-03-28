<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=0;$i<20;$i++){
            $book = new Book();
            $book->setAuthor("Testo");
            $book->setISPN("32BC35DS");
            $book->setPrice(3.50);
            $book->setName("Adventures Of Wow");
            $manager->persist($book);
        }

        $manager->flush();
    }
}
