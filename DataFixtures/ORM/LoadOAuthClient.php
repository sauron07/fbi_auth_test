<?php
/**
 * Created by PhpStorm.
 * User: maal
 * Date: 3/1/17
 * Time: 11:18 AM
 */

namespace Matvieiev\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadOAuthClient extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface Symfony container interface.
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
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $clientManager = $this->container->get('oauth2.client_manager');

        $scope = $this->getReference('scope')->getScope();
        $oauthClient = $clientManager->createClient(
            'oauth2_client',
            [
                'http://test.fbi/app_dev.php/'
            ],
            [],
            [
                $scope
            ]
        );
        $this->setReference('oauth_client', $oauthClient);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}