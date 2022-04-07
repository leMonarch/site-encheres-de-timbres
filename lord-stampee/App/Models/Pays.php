<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Pays extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAllPays()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM pays');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
