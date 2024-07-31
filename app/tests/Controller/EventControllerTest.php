<?php
namespace App\Tests\Controller;

use App\Entity\Contact;
use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\EventService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class Category Controller Test.
 */
class EventControllerTest extends WebTestCase
{
    public const TEST_ROUTE = '/event';
    private KernelBrowser $httpClient;
    private ?EntityManagerInterface $entityManager;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        $this->httpClient = static::createClient();
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->eventService = $container->get(EventService::class);
    }

    /**
     * Test index route as an anonymous user.
     */
    public function testIndexRouteAnonymousUser(): void
    {
        $expectedStatusCode = 302;
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route as admin user.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testIndexRouteAdminUser(): void
    {
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->httpClient->loginUser($adminUser);
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testIndexRouteNonAuthorizedUser(): void
    {
        $expectedStatusCode = 403;
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route as admin user.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testCreateRouteAnonymousUser(): void
    {
        $expectedStatusCode = 302;
        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testCreateRouteAdminUser(): void
    {
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->httpClient->loginUser($adminUser);
        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testCreateRouteNonAuthorizedUser(): void
    {
        $expectedStatusCode = 403;
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);
        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test show route as an anonymous user.
     */
    public function testShowRouteAnonymousUser(): void
    {
        $expectedStatusCode = 302;
        $contact = new Contact();
        $contact->setName('Test Contact');
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$contact->getId());
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testShowRouteAdminUser(): void
    {
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->httpClient->loginUser($adminUser);
        $contact = new Contact();
        $contact->setName('Test Contact');
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$contact->getId());
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testShowRouteNonAuthorizedUser(): void
    {
        $expectedStatusCode = 403;
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);
        $contact = new Contact();
        $contact->setName('Test Contact');
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$contact->getId());
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test edit route as an anonymous user.
     */
    public function testEditRouteAnonymousUser(): void
    {
        $expectedStatusCode = 302;
        $contact = new Contact();
        $contact->setName('Test Contact');
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$contact->getId().'/edit');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testEditRouteAdminUser(): void
    {
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->httpClient->loginUser($adminUser);
        $contact = new Contact();
        $contact->setName('Test Contact');
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$contact->getId().'/edit');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testEditRouteNonAuthorizedUser(): void
    {
        $expectedStatusCode = 403;
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);
        $contact = new Contact();
        $contact->setName('Test Contact');
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$contact->getId().'/edit');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test delete route as an anonymous user.
     */
    public function testDeleteRouteAnonymousUser(): void
    {
        $expectedStatusCode = 302;
        $contact = new Contact();
        $contact->setName('Test Contact');
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$contact->getId().'/delete');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testDeleteRouteAdminUser(): void
    {
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->httpClient->loginUser($adminUser);
        $contact = new Contact();
        $contact->setName('Test Contact');
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$contact->getId().'/delete');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testDeleteRouteNonAuthorizedUser(): void
    {
        $expectedStatusCode = 403;
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);
        $contact = new Contact();
        $contact->setName('Test Contact');
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$contact->getId().'/delete');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * @param array $roles User roles
     *
     * @return User User entity
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function createUser(array $roles): User
    {
        $hasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles($roles);
        $user->setPassword(
            $hasher->hashPassword(
                $user,
                'p@55w0rd'
            )
        );
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
    }
}
