<?php

namespace App\Tests\Controller;

trait ProvidePathsTrait
{
    /**
     * @return string[][]
     */
    public function provideTaskPaths(): array
    {
        return [
            'list_tasks' => ['/tasks'],
            'create_task' => ['/tasks/create'],
            'edit_task' => ['/tasks/1/edit'],
            'add_remove_toggle_task' => ['/tasks/1/toggle'],
            'remove_task' => ['/tasks/1/delete'],
        ];
    }

    /**
     * @return string[][]
     */
    public function provideUserPaths(): array
    {
        return [
            'list_tasks' => ['/tasks'],
            'create_task' => ['/tasks/create'],
            'edit_task' => ['/tasks/1/edit'],
            'add_remove_toggle_task' => ['/tasks/1/toggle'],
            'remove_task' => ['/tasks/1/delete'],
            'user_list' => ['/users/list'],
            'edit_user' => ['/users/1/edit'],
        ];
    }
}
