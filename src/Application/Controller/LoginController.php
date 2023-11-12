<?php

namespace Automator\Application\Controller;

use Automator\Application\Application;
use Automator\Application\Controller\Controller;
use Automator\Application\Model\User;
use Klein\Klein;

class LoginController extends Controller
{

    public function render($error = false) {
        $this->view("Login.latte", ["error" => $error]);
    }

    public function authenticate(string $username, string $password): User|null {
        $userRepository = Application::getInstance()->database->entityManager->getRepository(User::class);

        $user = $userRepository->findOneBy(["username" => $username]);
        if(!$user) return null;
        if(password_verify($password, $user->getPassword())) return $user;
        return null;
    }

    public function registerRoute(Klein $klein): void
    {
        $klein->get("/logout", function() {
            SessionController::$instance->destroyCurrentSession();
            header("Location: /");
            die();
        });
        $klein->get("/login", function() {
            $this->render();
        });

        $klein->post("/login", function() {
            $username = $_POST["username"] ?? null;
            $password = $_POST["password"] ?? null;
            $user = $this->authenticate($username, $password);

            if(!$user) {
                $this->render(true);
                die;
            }

            $currentSession = SessionController::$instance->getUserSession($user);
            if($currentSession) {
                Application::getInstance()->database->entityManager->remove($currentSession);
                Application::getInstance()->database->entityManager->flush();
            }

            $session = SessionController::$instance->getCurrentSession();
            $session->setUser($user);
            Application::getInstance()->database->entityManager->persist($user);
            Application::getInstance()->database->entityManager->flush();

            header("Location: /");
            die;
        });
    }
}