<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;


class UserVoter extends Voter
{

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['USER_EDIT', 'USER_VIEW', 'USER_DELETE'])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        
        // $user contient les informations de la personne connectée
        $user = $token->getUser();


        // Si on est pas connecté, on interdit automatiquement l'accès à la page d'édition d'un utilisateur
        if (!$user instanceof UserInterface) {
            return false;
        }
        
        // ... (check conditions and return true to grant permission) ...
        // On a accès à la page d'édition d'un utilisateur si : 
        // - On est super_admin : on a tous les droits
        // - On est Admin et on tente d'éditer un compte role_user.
        // - Dans tous les autres cas, on retourne une 403 (access denied).
        
        switch ($attribute) {
            case 'USER_EDIT':
                // Si la personne connectée est super Admin, alors on autorise l'accès 
                // à la page d'édition d'un utilisateur
                if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
                    return true;
                }

                // Un administrateur a le droit d'éditer son propre compte
                // Si l'utilisateur connecté ($user) est égal à l'utilisateur
                // dont on souhaite éditer le compte ($subject)
                // alors on autorise l'accès à la page d'édition

                $roles = $subject->getRoles();
                if (count($roles) == 1 && $roles[0] == 'ROLE_USER') {
                    return true;
                }

                if ($user == $subject) {
                    return true;
                }

                // Un administrateur peut éditer le compte d'un simple utilisateur
                // On va vérifier que le compte à éditer à un role user

                // if ()


                // Un administrateur n'a pas le droit d'éditer le compte d'un autre admin


                break;
            case 'POST_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}