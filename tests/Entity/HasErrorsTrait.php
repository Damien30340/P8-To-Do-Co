<?php

namespace App\Tests\Entity;

use Symfony\Component\Validator\ConstraintViolation;

trait HasErrorsTrait
{
    public function assertHasErrors(object $subject, int $number): void
    {
        self::bootKernel();
        $errors = self::$kernel->getContainer()->get('validator')->validate($subject);
        $messages = [];

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath().' => '.$error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }
}
