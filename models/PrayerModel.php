<?php

namespace Models;

use Utils\Database;
use Utils\EmailUtils;

class PrayerModel
{
    public function getBoxPrayersForToday($userId)
    {
        $conn = Database::getConnection();
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $today = date('Y-m-d');

        $boxesQuery = '
            SELECT box_id, box_name, prayerzone
            FROM box
            WHERE subs_id = :user_id
        ';
        $stmtBoxes = $conn->prepare($boxesQuery);
        $stmtBoxes->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmtBoxes->execute();
        $allBoxes = $stmtBoxes->fetchAll(\PDO::FETCH_ASSOC);

        $prayerQuery = "
            SELECT song_title, prayertime, prayertimedate
            FROM song
            WHERE box_id = :box_id AND prayertimedate = :today AND status = 'scheduled'
            ORDER BY created_at DESC, prayertime ASC
        ";
        $stmtPrayers = $conn->prepare($prayerQuery);
        $stmtPrayers->bindParam(':today', $today);

        foreach ($allBoxes as $key => $box) {
            $stmtPrayers->bindParam(':box_id', $box['box_id'], \PDO::PARAM_INT);
            $stmtPrayers->execute();
            $prayers = $stmtPrayers->fetchAll(\PDO::FETCH_ASSOC);

            if (empty($prayers)) {
                $prayers = [['song_title' => 'No prayer times found', 'prayertime' => '']];
            }

            $allBoxes[$key]['prayers'] = $prayers;
        }

        return $allBoxes;
    }

    public function updatePrayerTimes()
    {
        $zones = $this->getDistinctPrayerZones();
        $today = date('Y-m-d');

        foreach ($zones as $zone) {
            $url = "https://www.e-solat.gov.my/index.php?r=esolatApi/TakwimSolat&period=week&zone=$zone";
            $json = file_get_contents($url);
            $data = json_decode($json, true);

            if ($data['status'] == 'OK!') {
                foreach ($data['prayerTime'] as $day) {
                    $originalDateStr = $day['date'];

                    $dateTime = \DateTime::createFromFormat('d-M-Y', $originalDateStr);

                    $date = $dateTime->format('Y-m-d');

                    $prayers = [
                        'Fajr' => $day['fajr'],
                        'Dhuhr' => $day['dhuhr'],
                        'Asr' => $day['asr'],
                        'Maghrib' => $day['maghrib'],
                        'Isha' => $day['isha'],
                    ];

                    $boxId = $this->getBoxIdForZone($zone);
                    foreach ($prayers as $prayerName => $time) {
                        $this->storePrayerTime($boxId, $zone, $prayerName, $date, $time);
                    }
                }
            }
        }
    }

    public function getDistinctPrayerZones()
    {
        $conn = Database::getConnection();
        $query = 'SELECT DISTINCT prayerzone FROM box';
        $stmt = $conn->prepare($query);
        $stmt->execute();

        // Fetch all distinct zones as an array
        $zones = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

        return $zones; // Returns an array of unique prayerzone values
    }

    public function getBoxIdForZone($zone)
    {
        $conn = Database::getConnection(); // Assume this method returns your PDO database connection
        $query = 'SELECT box_id FROM box WHERE prayerzone = :zone LIMIT 1';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':zone', $zone, \PDO::PARAM_STR);
        $stmt->execute();

        // Fetch the first box_id found for the given zone
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ? $result['box_id'] : null;
    }

    public function storePrayerTime($boxId, $zone, $prayerName, $date, $time)
    {
        try {
            $conn = Database::getConnection(); // Retrieves your PDO database connection

            // Fetch the subs_id for the given box_id
            $subsIdQuery = 'SELECT subs_id FROM box WHERE box_id = :box_id LIMIT 1';
            $stmtSubsId = $conn->prepare($subsIdQuery);
            $stmtSubsId->bindParam(':box_id', $boxId, \PDO::PARAM_INT);
            $stmtSubsId->execute();
            $subsIdResult = $stmtSubsId->fetch(\PDO::FETCH_ASSOC);

            if (!$subsIdResult) {
                // Handle error or log if the box_id does not exist
                error_log("Box ID $boxId not found in the database.");

                return; // Exit the function if no corresponding box is found
            }

            $subsId = $subsIdResult['subs_id']; // Extract subs_id from the query result

            // Prepare the SQL statement to insert a new prayer time record
            $query = "
            INSERT INTO song (song_title, subs_id, box_id, prayerzone, prayertimedate, prayertime, prayertimeseq, status)
            VALUES (:song_title, :subs_id, :box_id, :prayerzone, :prayertimedate, :prayertime, :prayertimeseq, 'scheduled')
            ON DUPLICATE KEY UPDATE
            prayertime = VALUES(prayertime), prayertimeseq = VALUES(prayertimeseq), status = VALUES(status)
        ";

            $stmt = $conn->prepare($query);

            // Determine the prayer sequence based on the prayer name
            $prayerSequence = $this->getPrayerSequence($prayerName);

            // Bind parameters to the prepared statement
            $stmt->execute([
                ':song_title' => $prayerName,
                ':subs_id' => $subsId,
                ':box_id' => $boxId,
                ':prayerzone' => $zone,
                ':prayertimedate' => $date,
                ':prayertime' => $time,
                ':prayertimeseq' => $prayerSequence,
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            EmailUtils::sendErrorEmail($boxId, $zone, $e->getMessage());
        }
    }

    public function getPrayerSequence($prayerName)
    {
        // Define a map of prayer names to their sequence numbers
        $prayerSequenceMap = [
            'Fajr' => 1,
            'Dhuhr' => 2,
            'Asr' => 3,
            'Maghrib' => 4,
            'Isha' => 5,
        ];

        // Return the sequence number for the given prayer name,
        // or null if the prayer name is not found in the map
        return isset($prayerSequenceMap[$prayerName]) ? $prayerSequenceMap[$prayerName] : null;
    }
}
