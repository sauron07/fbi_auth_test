<?php

namespace Matvieiev\LoginBundle\Service;

use Matvieiev\LoginBundle\Entity\User as UserEntity;

/**
 * Interface UserServiceInterface
 *
 * @package Matvieiev\LoginBundle\Service
 */
interface UserServiceInterface
{
    /**
     * Update user token.
     *
     * @param UserEntity $user  User.
     * @param string     $token Token.
     *
     * @return void
     */
    public function updateUserToken(UserEntity $user, $token);

    /**
     * Clear user token.
     *
     * @param UserEntity $user User entity.
     *
     * @return void
     */
    public function clearUserToken(UserEntity $user);

    /**
     * Update last log in date.
     *
     * @param UserEntity $user User instance.
     *
     * @return void
     */
    public function updateLastLoginDate(UserEntity $user);
}