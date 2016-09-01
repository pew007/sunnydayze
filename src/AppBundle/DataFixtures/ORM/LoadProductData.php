<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Product;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProductData implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $product = new Product();
        $product->setName("Radar");
        $product->setAmountAvailable(100);
        $product->setCost(80);
        $product->setPrice(120);
        $product->setSku('OAK123');
        $product->setDescription("This is for test only");

        $manager->persist($product);
        $manager->flush();
    }
}