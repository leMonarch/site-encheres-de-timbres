<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Image extends \Core\Model
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
    public static function getImage($id)
    {
        $db = static::getDB();
        $sql = 'SELECT * FROM images WHERE image_id = :image_id';
        $stmt = $db->prepare($sql);
            $stmt->execute([
                ':image_id' => $id,
            ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
