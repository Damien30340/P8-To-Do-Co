<?php

namespace Tests\AppBundle\Entity;

use Symfony\Component\Validator\ConstraintViolation;

trait HasErrorsTrait
{
    public function assertHasErrors($object, $number){
        self::bootKernel();
        $errors = self::$kernel->getContainer()->get('validator')->validate($object);
        $messages = [];

        /** @var ConstraintViolation $error */
        foreach ($errors as $error){
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }
}