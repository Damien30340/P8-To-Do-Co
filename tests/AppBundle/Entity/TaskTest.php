<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    use HasErrorsTrait;


    /**
     * @return Task
     */
    public function getEntity(){
        $task = new Task();
        $task->setTitle('titleTest');
        $task->setContent('contentTest');
        $task->setCreatedAt(new \DateTime());

        return $task;
    }

    public function testValidTaskEntity(){
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidBlankTitle(){
        $task = new Task();
        $task->setContent('Test');
        $this->assertHasErrors($task, 1);
    }

    public function testInvalidBlankContent(){
        $task = new Task();
        $task->setTitle('Test');
        $this->assertHasErrors($task, 1);
    }

    public function testGetIdTask()
    {
        $task = new Task();
        $this->assertSame(null, $task->getId());
    }

    public function testCreatedDateTask()
    {
        $task = new Task();
        $this->assertInstanceOf(\DateTime::class, $task->getCreatedAt());
    }

    public function testIsDoneDefaultTask()
    {
        $task = new Task();
        $this->assertSame(false, $task->isDone());
    }

    public function testIsDoneAfterToggleTask()
    {
        $task = new Task();
        $task->toggle(true);
        $this->assertSame(true, $task->isDone());
    }
}
