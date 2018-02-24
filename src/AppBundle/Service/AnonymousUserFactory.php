<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class AnonymousUserFactory
{
    const ANONYMOUS_USERNAME = 'anonyme';

    const ANONYMOUS_EMAIL = 'admin@todolist.com';

    const ANONYMOUS_PASSWORD = 'anonymous';

    private $em;

    private $encoder;

    public function __construct(EntityManager $em, UserPasswordEncoder $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    public function getAnonymous()
    {
        $anonymous = $this->em->getRepository(User::class)
            ->findOneBy(array(
                'username' => self::ANONYMOUS_USERNAME
            ));

        if (null === $anonymous) {
            $anonymous = new User();
            $anonymous->setRole('ROLE_USER');
            $anonymous->setUsername(self::ANONYMOUS_USERNAME);
            $anonymous->setEmail(self::ANONYMOUS_EMAIL);
            $anonymous->setPassword(
                $this->encoder->encodePassword($anonymous, self::ANONYMOUS_PASSWORD)
            );

            $this->em->persist($anonymous);
            $this->em->flush();
        }

        return $anonymous;
    }
}