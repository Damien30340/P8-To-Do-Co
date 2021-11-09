<?php

namespace App\Controller;

interface EntityPersisterInterface
{
    /**
     * Update or Create of EntityPersisterInterface
     * The param method must be of type object
     *
     * Call EntityManagerInterface for create of update the object of the bdd
     * Expected in the method :
     *          $this->em->persist($object)
     *          $this->em->flush()
     * @param $obj
     * @return void
     */
    public function update($obj);
    /**
     * Delete of EntityPersisterInterface
     * The param method must be of type object
     *
     * Call EntityManagerInterface for remove the object of the bdd
     * Expected in the method :
     *          $this->em->persist($object)
     *          $this->em->flush()
     * @param $obj
     * @return void
     */
    public function delete($obj);
}