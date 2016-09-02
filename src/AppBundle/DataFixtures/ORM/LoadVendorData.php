<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Vendor;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadVendorData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $vendors = ['Oakley', 'Chanel', 'Ray-Ban', 'Prada', 'Gucci'];
        foreach ($vendors as $vendorName) {
            $vendor = new Vendor($vendorName);

            $manager->persist($vendor);
            $manager->flush();

            $this->addReference("vendor-{$vendor->getId()}", $vendor);
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
