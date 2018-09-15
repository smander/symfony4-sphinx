<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $product = new Article();
            $product->setText('product '.$i);
            $product->setUserid(mt_rand(10, 100));
            $manager->setDate(date("Y-m-d H:i:s"));
            $manager->setNOfViews(mt_rand(10, 100));
            $manager->setNOfComments(mt_rand(10, 100));
        }

        $manager->flush();
    }
}