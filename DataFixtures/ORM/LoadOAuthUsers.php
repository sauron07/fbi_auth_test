<?php
/**
 * Created by PhpStorm.
 * User: maal
 * Date: 2/28/17
 * Time: 6:05 PM
 */

namespace Matvieiev\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Matvieiev\LoginBundle\Entity\User;
use OAuth2\ServerBundle\User\OAuth2UserProvider;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadOAuthUsers extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface Symfony container.
     */
    private $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures for employees.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var OAuth2UserProvider $userProvider */
        $userProvider = $this->container->get('oauth2.user_provider');
        $scope = $this->getReference('scope')->getScope();

        // Create worker user.
        $workerUser = $userProvider->createUser(
            'worker',
            'worker',
            [
                User::USER_ROLE_WORKER
            ],
            [
                $scope
            ]
        );
        $this->setReference('worker_user', $workerUser);

        //Crate manager user.
        $managerUser = $userProvider->createUser(
            'manager',
            'manager',
            [
                User::USER_ROLE_WORKER,
                User::USER_ROLE_MANAGER,
            ],
            [
                $scope
            ]
        );
        $this->setReference('manager_user', $managerUser);

        $adminUser = $userProvider->createUser(
            'admin',
            'admin',
            [
                User::USER_ROLE_WORKER,
                User::USER_ROLE_MANAGER,
                User::USER_ROLE_ADMIN
            ],
            [
                $scope
            ]
        );
        $this->setReference('admin_user', $adminUser);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}