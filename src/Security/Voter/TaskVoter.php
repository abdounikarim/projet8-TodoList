<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return 'TASK_DELETE' === $attribute
            && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ('TASK_DELETE' === $attribute) {
            // logic to determine if the user can VIEW
            /** @var Task $subject */
            if ($subject->getUser() === $user) {
                return true;
            }

            if (null === $subject->getUser() && in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                return true;
            }
        }

        return false;
    }
}
