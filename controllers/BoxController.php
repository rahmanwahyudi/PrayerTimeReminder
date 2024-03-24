<?php

namespace Controllers;

use Models\BoxModel;

class BoxController
{
    use ViewLoaderTrait;

    private $model;
    private $resources;
    private $controller;

    public function __construct()
    {
        $this->model = new BoxModel();
        $this->controller = new PrayerController();
        $this->resources = [
            'header' => ['css' => ['assets/css/subscriber.css']],
        ];
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleBoxCreation();
        } else {
            $this->view('box/create', [], $this->resources);
        }
    }

    public function edit($boxId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleBoxEdit($boxId);
        } else {
            $boxDetails = $this->model->getBoxById($boxId);
            if ($boxDetails) {
                $this->view('box/edit', ['box' => $boxDetails], $this->resources);
            } else {
                $this->view('box/edit', ['error' => 'Box not found.'], $this->resources);
            }
        }
    }

    private function handleBoxCreation()
    {
        if (empty($_POST['box_name']) || empty($_POST['prayer_zone'])) {
            $this->view('box/create', ['error' => 'Please fill in all required fields'], $this->resources);

            return;
        }

        $boxName = $_POST['box_name'];
        $prayerZone = $_POST['prayer_zone'];
        $userId = $_SESSION['user_id'] ?? null;

        try {
            $this->model->createBox($userId, $boxName, $prayerZone);
            $this->controller->updatePrayerTimes();
            header('Location: '.\Utils\Env::base_url('/'));
            exit;
        } catch (\Exception $e) {
            $this->view('box/create', ['error' => 'An error occurred. Please try again.'], $this->resources);
        }
    }

    private function handleBoxEdit($boxId)
    {
        if (empty($_POST['box_name']) || empty($_POST['prayer_zone'])) {
            $this->view('box/edit', ['error' => 'Please fill in all required fields', 'boxId' => $boxId], $this->resources);

            return;
        }

        $boxName = $_POST['box_name'];
        $prayerZone = $_POST['prayer_zone'];

        try {
            $this->model->editBox($boxId, $boxName, $prayerZone);
            $this->controller->updatePrayerTimes();
            header('Location: '.\Utils\Env::base_url('/boxes'));
            exit;
        } catch (\Exception $e) {
            $this->view('box/edit', ['error' => 'An error occurred. Please try again.', 'boxId' => $boxId], $this->resources);
        }
    }
}
