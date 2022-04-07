<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Certification extends \Core\Model
{

    /**
     * Get all the certifications as an associative array
     *
     * @return array
     */
    public static function getAllCertification()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM certifications');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
