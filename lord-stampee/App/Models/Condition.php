<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Condition extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAllCondition()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM conditions');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
