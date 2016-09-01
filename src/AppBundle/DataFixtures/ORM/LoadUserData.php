<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setEmail('bird0407@gmail.com');
        $admin->setPassword('Wpy@0407');
        $admin->setFirstName('Pengyu');
        $admin->setLastName('Wang');
        $admin->setIsAdmin(true);

        $manager->persist($admin);
        $manager->flush();
    }
}
