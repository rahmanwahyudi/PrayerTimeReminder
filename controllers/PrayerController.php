<?php

namespace Controllers;

use Models\PrayerModel;

class PrayerController
{
    use ViewLoaderTrait;

    private $model;

    public function __construct()
    {
        $this->model = new PrayerModel();
    }

    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            $boxesWithPrayers = $this->model->getBoxPrayersForToday($userId);

            $data = ['boxes' => $boxesWithPrayers];
        } else {
            $data = [];
        }

        $resources = [];

        $this->view('home/index', $data, $resources);
    }

    public function updatePrayerTimes()
    {
        try {
            $this->model->updatePrayerTimes();
            echo 'Prayer times updated successfully.';
        } catch (\Exception $e) {
            echo 'Failed to update prayer times: '.$e->getMessage();
        }
    }
}
