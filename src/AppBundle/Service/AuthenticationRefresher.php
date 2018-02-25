<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthenticationRefresher
{
    private $manager;

    private $tokenStorage;

    public function __construct(EntityManagerInterface $manager, TokenStorageInterface $tokenStorage)
    {
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
    }

    public function refreshIfAuthenticated(User $user)
    {
        $authenticatedUser = $this->tokenStorage->getToken()->getUser();
        if (is_object($authenticatedUser) && $authenticatedUser->getUsername() == $user->getUsername()) {
            $freshUser = $this->manager->getRepository(User::class)->refreshUser($authenticatedUser);
            $this->tokenStorage->setToken(
                new UsernamePasswordToken(
                    $freshUser,
                    $freshUser->getPassword(),
                    'main',
                    $freshUser->getRoles()
                )
            );
        }
    }
}