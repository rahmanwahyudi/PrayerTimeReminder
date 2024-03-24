<?php

namespace Models;

use Utils\Database;

class SubscriberModel
{
    public function addSubscriber($subs_name, $email, $password, $phone)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('INSERT INTO subscriber (subs_name, email, password, phone) VALUES (?, ?, ?, ?)');
        $stmt->execute([$subs_name, $email, password_hash($password, PASSWORD_DEFAULT), $phone]);

        return $conn->lastInsertId();
    }

    public function loginSubscriber($email, $password)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM subscriber WHERE email = ?');
        $stmt->execute([$email]);

        $subscriber = $stmt->fetch();

        if ($subscriber) {
            if (password_verify($password, $subscriber['password'])) {
                return $subscriber;
            }
        }

        return false;
    }
}
