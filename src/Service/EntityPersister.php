<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class EntityPersister
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function update(object $obj): void
    {
        $this->em->persist($obj);
        $this->em->flush();
    }
    public function delete(object $obj): void
    {
        $this->em->remove($obj);
        $this->em->flush();
    }
}