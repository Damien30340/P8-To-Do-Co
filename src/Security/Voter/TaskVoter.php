<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    const TASK_DELETE = 'TASK_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return $attribute == self::TASK_DELETE
            && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        if($attribute === self::TASK_DELETE){
            if($this->canRemove($subject, $user) === true){
                return true;
            }
        }
        return false;
    }

    private function canRemove(Task $task, UserInterface $user): bool
    {
        if ($task->getUser() === null) {
            if(in_array('ROLE_ADMIN', $user->getRoles())){
                return true;
            }
            else{
                return false;
            }
        }

        if ($task->getUser() === $user)
        {
            return true;
        } else{
            return false;
        }
    }

}
