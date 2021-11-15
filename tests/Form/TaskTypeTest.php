<?php

namespace App\Tests\Form;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $title = 'Test title';
        $content = 'Test Content';

        $formData = [
            'title' => $title,
            'content' => $content,
        ];

        $object = new Task();
        $object->setTitle($title);
        $object->setContent($content);

        $objectToCompare = new Task();

        $form = $this->factory->create(TaskType::class, $objectToCompare);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($object->getTitle(), $objectToCompare->getTitle());
        $this->assertEquals($object->getContent(), $objectToCompare->getContent());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
