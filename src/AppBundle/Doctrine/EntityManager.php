<?php

namespace AppBundle\Doctrine;

class EntityManager
{
    /** @var  \Doctrine\ORM\EntityManager */
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function getRepository($entityName)
    {
        return $this->em->getRepository($entityName);
    }

    public function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
