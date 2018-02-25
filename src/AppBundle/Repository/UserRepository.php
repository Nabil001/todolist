<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Service\AnonymousUserFactory;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    public function findAllAuthors()
    {
        $authors = $this->findAll();

        return array_filter($authors, function ($author) {
            return $author->getUsername() != AnonymousUserFactory::ANONYMOUS_USERNAME;
        });
    }

    public function loadUserByUsername($username)
    {
        return $this->findOneBy(['username' => $username]);
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return User::class == $class;
    }
}