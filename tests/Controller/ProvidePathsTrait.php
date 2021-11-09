<?php

namespace App\Tests\Controller;

trait ProvidePathsTrait
{
    public function provideTaskPaths()
    {
        return array(
            "list_tasks" => ["/tasks"],
            "create_task" => ["/tasks/create"],
            "edit_task" => ["/tasks/1/edit"],
            "add_remove_toggle_task" => ["/tasks/1/toggle"],
            "remove_task" => ["/tasks/1/delete"]
        );
    }

    public function provideUserPaths()
    {
        return array(
            "list_tasks" => ["/tasks"],
            "create_task" => ["/tasks/create"],
            "edit_task" => ["/tasks/1/edit"],
            "add_remove_toggle_task" => ["/tasks/1/toggle"],
            "remove_task" => ["/tasks/1/delete"],
            "user_list" => ["/admin/users"],
            "edit_user" => ["/users/1/edit"]
        );
    }
}