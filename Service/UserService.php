<?php

namespace Matvieiev\LoginBundle\Service;


use Doctrine\ORM\EntityManager;
use Matvieiev\LoginBundle\Entity\User as UserEntity;

/**
 * Class UserService
 *
 * @package Matvieiev\LoginBundle\Service
 */
class UserService implements UserServiceInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Update user token value.
     *
     * @param UserEntity $user  User entity.
     * @param string     $token Token value.
     *
     * @return void
     */
    public function updateUserToken(UserEntity $user, $token)
    {
        $user->setToken($token);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Update last login date.
     *
     * @param UserEntity $user User entity.
     *
     * @return void
     */
    public function updateLastLoginDate(UserEntity $user)
    {
        $user->setLastLoginDate(new \DateTime());
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Clear user token.
     *
     * @param UserEntity $user User entity.
     *
     * @return void
     */
    public function clearUserToken(UserEntity $user)
    {
        $user->setToken(null);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}