<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Timbre extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getStamp($data)
    {
        $db = static::getDB();
        $sql = 'SELECT * FROM timbres WHERE timbre_id = :timbre_id';
        $stmt = $db->prepare($sql);
            $stmt->execute([
                ':timbre_id' => $data['timbre_id'],
            ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
