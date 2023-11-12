<?php

namespace Automator\Application\Controller;

use Automator\Application\Application;
use Automator\Application\Controller\Controller;
use Automator\Application\Model\Session;
use Automator\Application\Model\User;
use Doctrine\ORM\EntityRepository;
use Klein\Klein;

class SessionController extends Controller
{

    public static SessionController $instance;

    private EntityRepository $sessionRepository;

    public function __construct()
    {
        self::$instance = $this;
    }

    public function registerRoute(Klein $klein): void
    {
        $this->sessionRepository = Application::getInstance()->database->entityManager->getRepository(Session::class);
    }

    public function generateEmptySession(): Session {
        $session = new Session();
        $session->setActive(true);
        $session->setCreated(new \DateTime());
        $session->setExpires((new \DateTime(date("Y-m-d H:i:s", strtotime("+12 hours")))));
        $session->setOverridesSession(false);
        $session->setIdentifier(__generate_random_string("96"));
        return $session;
    }

    public function destroyCurrentSession() {
        if (isset($_COOKIE['session'])) {
            $oldSessionId =$_COOKIE['session'];
            $oldSession = $this->sessionRepository->findOneBy(["identifier" => $oldSessionId]);
            if($oldSession) {
                Application::getInstance()->database->entityManager->remove($oldSession);
                Application::getInstance()->database->entityManager->flush();
            }

            unset($_COOKIE['session']);
            setcookie('session', '', time() - 3600);
        }
    }

    public function setSession(Session $session, bool $rememberMe) {
        if (isset($_COOKIE['session'])) {
            $oldSessionId = $_COOKIE['session'];
            $oldSession = $this->sessionRepository->findOneBy(["identifier" => $oldSessionId]);
            if($oldSession) {
                Application::getInstance()->database->entityManager->remove($oldSession);
            }
            unset($_COOKIE['session']);
            setcookie('session', '', time() - 3600);
        }
        if($rememberMe) {
            $sessionData = Application::getInstance()->database->entityManager->getReference(Session::class, $session->getIdentifier());
            $sessionData->setExpires((new \DateTime(date("Y-m-d H:i:s", strtotime("+30 days")))));
            Application::getInstance()->database->entityManager->flush();
            setcookie("session", $session->getIdentifier(), time() + (60 * 60 * 24 * 30));
        } else {
            setcookie("session", $session->getIdentifier(), time() + (60 * 60 * 12));
        }

    }

    public function getUserSession(User $user): Session|null {
        return $this->sessionRepository->findOneBy(["user" => $user]);
    }

    public function getCurrentSession(): Session|null {
        $session = null;
        $sessionId = isset($_COOKIE['session']) ? $_COOKIE["session"] : null;
        if(!$sessionId) {
            $session = $this->generateEmptySession();
            Application::getInstance()->database->entityManager->persist($session);
            Application::getInstance()->database->entityManager->flush($session);
            $this->setSession($session, false);
            return $session;
        }
        return $this->sessionRepository->findOneBy(["identifier" => $sessionId]);

    }

}