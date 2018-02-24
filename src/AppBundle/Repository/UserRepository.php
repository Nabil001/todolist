<?php

namespace AppBundle\Repository;

use AppBundle\Service\AnonymousUserFactory;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAllAuthors()
    {
        $authors = $this->findAll();
        
        return array_filter($authors, function ($author) {
            return $author->getUsername() != AnonymousUserFactory::ANONYMOUS_USERNAME;
        });
    }
}