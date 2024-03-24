<?php

namespace Models;

use Utils\Database;

class BoxModel
{
    public function createBox($userId, $boxName, $prayerZone)
    {
        $conn = Database::getConnection();

        $query = 'INSERT INTO box (subs_id, box_name, prayerzone) VALUES (:subs_id, :box_name, :prayer_zone)';
        $stmt = $conn->prepare($query);

        return $stmt->execute([
            ':subs_id' => $userId,
            ':box_name' => $boxName,
            ':prayer_zone' => $prayerZone,
        ]);
    }

    public function editBox($boxId, $boxName, $prayerZone)
    {
        $conn = Database::getConnection();

        $query = 'UPDATE box SET box_name = :box_name, prayerzone = :prayer_zone WHERE box_id = :box_id';
        $stmt = $conn->prepare($query);

        return $stmt->execute([
            ':box_id' => $boxId,
            ':box_name' => $boxName,
            ':prayer_zone' => $prayerZone,
        ]);
    }

    public function getBoxById($boxId)
    {
        $conn = Database::getConnection();

        $query = 'SELECT * FROM box WHERE box_id = :box_id LIMIT 1';
        $stmt = $conn->prepare($query);

        $stmt->execute([':box_id' => $boxId]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
