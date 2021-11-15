<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    use HasErrorsTrait;

    public function getEntity(): Task
    {
        $task = new Task();
        $task->setTitle('titleTest');
        $task->setContent('contentTest');
        $task->setCreatedAt(new \DateTime());

        return $task;
    }

    public function testValidTaskEntity(): void
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidBlankTitle(): void
    {
        $task = new Task();
        $task->setContent('Test');
        $this->assertHasErrors($task, 1);
    }

    public function testInvalidBlankContent(): void
    {
        $task = new Task();
        $task->setTitle('Test');
        $this->assertHasErrors($task, 1);
    }

    public function testGetIdTask(): void
    {
        $task = new Task();
        $this->assertSame(null, $task->getId());
    }

    public function testCreatedDateTask(): void
    {
        $task = new Task();
        $this->assertInstanceOf(\DateTime::class, $task->getCreatedAt());
    }

    public function testIsDoneDefaultTask(): void
    {
        $task = new Task();
        $this->assertSame(false, $task->isDone());
    }

    public function testIsDoneAfterToggleTask(): void
    {
        $task = new Task();
        $task->toggle(true);
        $this->assertSame(true, $task->isDone());
    }
}
