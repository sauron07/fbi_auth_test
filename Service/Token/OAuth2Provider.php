<?php

namespace Matvieiev\LoginBundle\Service\Token;


use GuzzleHttp\Client;
use Matvieiev\LoginBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Class OAuth2Provider
 *
 * @package Matvieiev\LoginBundle\Service\Token
 */
class OAuth2Provider implements TokenProviderInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * OAuth2Server constructor.
     *
     * @param $baseUri
     */
    public function __construct($baseUri)
    {
        $this->client = new Client(['base_uri' => $baseUri]);
    }

    /**
     * Verify oauth user token.
     *
     * @param string $clientId oAuth client id.
     * @param string $token    oAuth token.
     *
     * @return bool
     *
     * @throws BadCredentialsException
     */
    public function verifyToken($clientId, $token)
    {
        try {
            $result = $this->client->post('verify', [
                'form_params' => [
                    'client_id' => $clientId,
                    'access_token' => $token,
                ]
            ]);

            $content = $result->getBody()->getContents();
            $content = json_decode($content, true);

            return array_key_exists('client_id', $content);
        } catch (\Exception $exception) {
            throw new BadCredentialsException('Invalid token.');
        }
    }

    /**
     * Receive oAuth user token.
     *
     * @param User   $user     User entity.
     * @param string $password oAuth user password.
     *
     * @return string
     *
     * @throws BadCredentialsException
     */
    public function receiveUserToken(User $user, $password)
    {
        try {
            $result = $this->client->post('token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'username' => $user->getUsername(),
                    'password' => $password,
                    'client_id' => $user->getOAuthClientId(),
                    'client_secret' => $user->getOAuthClientSecret()
                ]
            ]);

            $content = $result->getBody()->getContents();
            $content = json_decode($content, true);

            return $content['access_token'];
        } catch (\Exception $exception) {
            throw new BadCredentialsException('Invalid username or password');
        }
    }

}