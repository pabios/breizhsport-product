<?php

namespace App\Domain\Service;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        // Crée un utilisateur à partir du champ "username" ou autre du JWT
        return new JwtUser($identifier, ['ROLE_ADMIN']); // Exemple avec rôle ADMIN
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new UnsupportedUserException('Refreshing users is not supported.');
    }

    public function supportsClass(string $class): bool
    {
        return JwtUser::class === $class;
    }
}
