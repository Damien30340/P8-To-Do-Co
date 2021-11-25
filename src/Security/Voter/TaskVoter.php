<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    public const TASK_DELETE = 'TASK_DELETE';

    /**
     * @param Task|null $subject
     */
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return self::TASK_DELETE === $attribute
            && $subject instanceof Task;
    }

    /**
     * @param Task|null $subject
     * @codeCoverageIgnore
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        return $subject->getUser() === $user || null === $subject->getUser() & in_array('ROLE_ADMIN', $user->getRoles());
    }
}
