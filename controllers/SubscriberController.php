<?php

namespace Controllers;

use Models\SubscriberModel;

class SubscriberController
{
    use ViewLoaderTrait;

    private $model;
    private $resources;

    public function __construct()
    {
        $this->model = new SubscriberModel();
        $this->resources = [
            'header' => ['css' => ['assets/css/subscriber.css']],
        ];
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSignup();
        } else {
            $this->view('subscriber/index', [], $this->resources);
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $subscriber = $this->model->loginSubscriber($email, $password);

            if ($subscriber) {
                $_SESSION['user_id'] = $subscriber['subs_id'];
                $_SESSION['user_name'] = $subscriber['subs_name'];
                header('Location: '.\Utils\Env::base_url('/'));
                exit;
            } else {
                $this->view('subscriber/login', ['error' => 'Invalid credentials.'], $this->resources);
            }
        } else {
            $this->view('subscriber/login', [], $this->resources);
        }
    }

    public function signOut()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }

        session_destroy();

        header('Location: '.\Utils\Env::base_url('/'));
        exit;
    }

    private function handleSignup()
    {
        if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['subs_name'])) {
            $this->view('subscriber/index', ['error' => 'Please fill in all required fields'], $this->resources);

            return;
        }

        $subs_name = $_POST['subs_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone = $_POST['phone'] ?? '';

        try {
            $this->model->addSubscriber($subs_name, $email, $password, $phone);
            $this->view('subscriber/index', ['success' => 'Signup successful!'], $this->resources);
        } catch (\Exception $e) {
            $this->view('subscriber/index', ['error' => 'An error occurred. Please try again.'], $this->resources);
        }
    }
}
