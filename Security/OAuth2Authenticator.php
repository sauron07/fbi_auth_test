<?php

namespace Matvieiev\LoginBundle\Security;


use Matvieiev\LoginBundle\Entity\User;
use Matvieiev\LoginBundle\Service\Token\OAuth2Provider;
use Matvieiev\LoginBundle\Service\Token\TokenProviderInterface;
use Matvieiev\LoginBundle\Service\UserServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;

/**
 * Class OAuth2Authenticator
 *
 * @package Matvieiev\LoginBundle\Security
 */
class OAuth2Authenticator implements SimpleFormAuthenticatorInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var OAuth2Provider
     */
    private $tokenProvider;
    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * OAuth2Authenticator constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     * @param TokenProviderInterface       $tokenProviderService
     * @param UserServiceInterface         $userService
     */
    public function __construct(UserPasswordEncoderInterface $encoder, TokenProviderInterface $tokenProviderService, UserServiceInterface $userService)
    {
        $this->encoder = $encoder;
        $this->tokenProvider = $tokenProviderService;
        $this->userService = $userService;
    }

    /**
     * Authenticate token.
     *
     * @param TokenInterface        $token        Token to authenticate.
     * @param UserProviderInterface $userProvider User provider instance.
     * @param string                $providerKey  Provider key.
     *
     * @return UsernamePasswordToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            /** @var User $user */
            $user = $userProvider->loadUserByUsername($token->getUsername());
        } catch (UsernameNotFoundException $exception) {
            throw new CustomUserMessageAuthenticationException('Invalid username or password');
        }

        $passwordValid = $this->encoder->isPasswordValid($user, $token->getCredentials());
        if ($passwordValid) {
            $oAuthToken = $user->getToken();

            if (!$oAuthToken) {
                $oAuthToken = $this->tokenProvider->receiveUserToken($user, $token->getCredentials());
                $this->userService->updateUserToken($user, $oAuthToken);
            } else {
                $valid = $this->tokenProvider->verifyToken($user->getOAuthClientId(), $oAuthToken);
                if (!$valid) {
                    $this->userService->clearUserToken($user);
                    $this->userService->updateUserToken($user, $oAuthToken);
                }
            }

            $this->userService->updateLastLoginDate($user);

            return new UsernamePasswordToken($user, $user->getPassword(), $providerKey, $user->getRoles());
        }
        throw new CustomUserMessageAuthenticationException('Invalid username or password');
    }

    /**
     * Check supported token.
     *
     * @param TokenInterface $token       Incoming token.
     * @param string         $providerKey Provider key.
     *
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * Create username password token instance.
     *
     * @param Request $request     Request.
     * @param string  $username    User name to log in.
     * @param string  $password    Password to log in.
     * @param string  $providerKey Provider key.
     *
     * @return UsernamePasswordToken
     */
    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}