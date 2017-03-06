<?php

namespace Matvieiev\LoginBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Entity(repositoryClass="Matvieiev\LoginBundle\Repository\UserRepository")
 * @HasLifecycleCallbacks
 */
class User implements UserInterface, \Serializable
{
    /**
     * @const User role types.
     */
    const USER_ROLE_WORKER = 'ROLE_WORKER',
        USER_ROLE_MANAGER = 'ROLE_MANAGER',
        USER_ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @var string
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="oauth_client_id", type="string", length=255)
     */
    private $oAuthClientId;

    /**
     * @var string
     *
     * @ORM\Column(name="oauth_client_secret", type="string", length=255)
     */
    private $oAuthClientSecret;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login_date", type="datetime")
     */
    private $lastLoginDate;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="simple_array", nullable=true)
     */
    private $roles;

    /**
     * TODO: Check that this works on log in action.
     *
     * @PrePersist
     */
    public function updateLastLoginDate()
    {
        $this->setLastLoginDate(new \DateTime());
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastLoginDate()
    {
        return $this->lastLoginDate;
    }

    /**
     * @param \DateTime $lastLoginDate
     *
     * @return User
     */
    public function setLastLoginDate(\DateTime $lastLoginDate): User
    {
        $this->lastLoginDate = $lastLoginDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getOAuthClientId()
    {
        return $this->oAuthClientId;
    }

    /**
     * @param string $oAuthClientId
     *
     * @return User
     */
    public function setOAuthClientId(string $oAuthClientId)
    {
        $this->oAuthClientId = $oAuthClientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getOAuthClientSecret()
    {
        return $this->oAuthClientSecret;
    }

    /**
     * @param string $oAuthClientSecret
     *
     * @return User
     */
    public function setOAuthClientSecret(string $oAuthClientSecret)
    {
        $this->oAuthClientSecret = $oAuthClientSecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }
    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    /**
     * String representation of object
     *
     * @link  http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->username,
            $this->password,
            $this->lastName,
            $this->firstName,
            $this->email
        ));
    }

    /**
     * Constructs the object
     *
     * @link  http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     *
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->username,
            $this->password,
            $this->lastName,
            $this->firstName,
            $this->email
            ) = unserialize($serialized);
    }
}

