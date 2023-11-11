<?php

namespace Automator\Application;

use Automator\Application\Model\Session;
use Automator\Application\Model\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\ToolsException;

class Database
{

    public ?Connection $connection;
    public ?EntityManager $entityManager;
    private Application $application;

    /**
     * @throws MissingMappingDriverImplementation
     * @throws ToolsException
     * @throws Exception
     */
    public function __construct(Application $application) {
        $this->application = $application;
        $ormConfig = ORMSetup::createAttributeMetadataConfiguration(
            paths: array(__DIR__ . "/Model/"),
            isDevMode: true,
        );

        $this->connection = DriverManager::getConnection([
            'driver' => $this->application->config("database.driver", "sqlite3"),
            'path' => $this->application->config("database.path", __DIR__ . "/../../config/db.sqlite"),
        ], $ormConfig);

        $this->entityManager = new EntityManager($this->connection, $ormConfig);
        $users = $this->entityManager->getRepository(User::class)->findAll();
        if(is_countable($users) && count($users) == 0) {
            // Create admin user
            $user = new User();
            $user->setUsername("admin");
            $user->setDisplayName("Administrator");
            $user->setCreated(new \DateTime());
            $user->setLastLogin(new \DateTime());
            $user->setEmail("admin@example.com");
            $user->setAvatarUrl("");
            $user->setPassword(password_hash("password", PASSWORD_BCRYPT));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    /**
     * @throws ToolsException
     */
    private function createTable($class) {
        $metadata = $this->entityManager->getClassMetadata($class);
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
// you can drop the table like this if necessary
        $schemaTool->dropSchema(array($metadata));
        $schemaTool->createSchema(array($metadata));
    }

}