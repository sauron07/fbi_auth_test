<?php

namespace Matvieiev\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Matvieiev\LoginBundle\Entity\User;
use OAuth2\ServerBundle\Entity\Client;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Class LoadUsers
 *
 * @package Matvieiev\DataFixtures\ORM
 */
class LoadUsers extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Set up symfony container.
     *
     * @param ContainerInterface|null $container
     *
     * @return void.
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var Client $oAuthClient */
        $oAuthClient = $this->getReference('oauth_client');
        $encoderFactory = $this->container->get('security.encoder_factory')->getEncoder(User::class);
        $manager->persist($this->createWorkerUser($encoderFactory, $oAuthClient));
        $manager->persist($this->createManagerUser($encoderFactory, $oAuthClient));
        $manager->persist($this->createAdminUser($encoderFactory, $oAuthClient));

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4;
    }

    /**
     * Create worker user.
     *
     * @param PasswordEncoderInterface $encoderFactory Encoder factory.
     * @param Client                   $oAuthClient    oAuth client.
     *
     * @return User
     */
    private function createWorkerUser(PasswordEncoderInterface $encoderFactory, Client $oAuthClient)
    {
        $oauthWorkerUser = $this->getReference('worker_user');
        $user = new User();
        $user->setPassword($encoderFactory->encodePassword('worker', $user->getSalt()))
            ->setUsername($oauthWorkerUser->getUsername())
            ->setLastName('Last')
            ->setFirstName('First')
            ->setOAuthClientId($oAuthClient->getClientId())
            ->setOAuthClientSecret($oAuthClient->getClientSecret())
            ->setRoles([
                User::USER_ROLE_WORKER
            ])
            ->setEmail('worker@test.com');
        return $user;
    }

    /**
     * Create manager user.
     *
     * @param PasswordEncoderInterface $encoderFactory Encoder factory.
     * @param Client                   $oAuthClient    oAuth client.
     *
     * @return User
     */
    private function createManagerUser(PasswordEncoderInterface $encoderFactory, Client $oAuthClient)
    {
        /** @var \OAuth2\ServerBundle\Entity\User $oauthManagerUser */
        $oauthManagerUser = $this->getReference('manager_user');
        $user = new User();
        $user->setUsername($oauthManagerUser->getUsername())
            ->setPassword($encoderFactory->encodePassword('manager', $user->getSalt()))
            ->setLastName('Last')
            ->setFirstName('First')
            ->setOAuthClientId($oAuthClient->getClientId())
            ->setOAuthClientSecret($oAuthClient->getClientSecret())
            ->setRoles([
                User::USER_ROLE_MANAGER
            ])
            ->setEmail('manager@test.com');
        return $user;
    }

    /**
     * Create admin user.
     *
     * @param PasswordEncoderInterface $encoderFactory Encoder factory.
     * @param Client                   $oAuthClient    oAuth client.
     *
     * @return User
     */
    private function createAdminUser(PasswordEncoderInterface $encoderFactory, Client $oAuthClient)
    {
        /** @var \OAuth2\ServerBundle\Entity\User $oauthAdminUser */
        $oauthAdminUser = $this->getReference('admin_user');
        $user = new User();
        $user->setPassword($encoderFactory->encodePassword('admin', $user->getSalt()))
            ->setUsername($oauthAdminUser->getUsername())
            ->setLastName('Last')
            ->setFirstName('First')
            ->setOAuthClientId($oAuthClient->getClientId())
            ->setOAuthClientSecret($oAuthClient->getClientSecret())
            ->setRoles([
                User::USER_ROLE_ADMIN
            ])
            ->setEmail('admin@test.com');

        return $user;
    }
}