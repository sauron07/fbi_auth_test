<?php

namespace Matvieiev\LoginBundle\Service\Token;

use Matvieiev\LoginBundle\Entity\User;

/**
 * Interface TokenProviderInterface
 *
 * @package Matvieiev\LoginBundle\Service
 */
interface TokenProviderInterface
{
    /**
     * Verify oauth user token.
     *
     * @param string $clientId oAuth client id.
     * @param string $token    oAuth token.
     *
     * @return bool
     */
    public function verifyToken($clientId, $token);

    /**
     * Receive oAuth user token.
     *
     * @param User   $user     User entity.
     * @param string $password oAuth user password.
     *
     * @return string
     */
    public function receiveUserToken(User $user, $password);
}