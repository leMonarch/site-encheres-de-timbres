<?php

namespace App\Models;

use Exception;
use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Mise extends \Core\Model
{

    public static function update($data)
    {
        $msg = [];
        $db = static::getDB();
        
        $sql = 'SELECT enchere_prix_actuel
        FROM encheres 
        WHERE enchere_id = :enchere_id';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':enchere_id' => $data['enchere_id']
        ]);
        $prix = $stmt->fetch(PDO::FETCH_ASSOC);
        if($data['offre_mise_actuelle'] != ''){
            if($data['offre_mise_actuelle'] < $prix['enchere_prix_actuel']){ 
               
                $msg['message'] = 'Miser plus que l\'offre actuelle.';
            } else {
                $sql = 'UPDATE encheres 
                SET enchere_prix_actuel = :enchere_prix_actuel, gagnant_nom = :gagnant_nom
                WHERE enchere_id = :enchere_id';
                $stmt = $db->prepare($sql);
                $stmt->execute([
                ':enchere_id' => $data['enchere_id'],
                ':enchere_prix_actuel' => $data['offre_mise_actuelle'],
                ':gagnant_nom' => $_SESSION['current_user']['user_firstname']
                ]);
            }
        } else {
            $msg['message'] = 'Aucune mise effectuée.';
        }
        
        return $msg;
    }


    /**
     * Get mise actuelle du current user as an associative array
     *
     * @return array
     */
    public static function getIdGagnantEnListe($data)
    {
        $db = static::getDB();
        $sql = 'SELECT Offres_user_id
                        FROM offres
                        WHERE Offres_enchere_id = :Offres_enchere_id';
        $stmt = $db->prepare($sql);
            $stmt->execute([
                ':Offres_enchere_id' => $data['enchere_id']
            ]);
        $offre = $stmt->fetch(PDO::FETCH_ASSOC);
        if(isset($offre)){
            return $offre;
        }
        return false;
    }

    /**
     * Get mise actuelle du current user as an associative array
     *
     * @return array
     */
    public static function getGagnantEnListe($id)
    {
        $db = static::getDB();
        $sql = 'SELECT user_firstname
                        FROM users
                        INNER JOIN offres ON Offres_user_id = user_id
                        WHERE user_id = Offres_user_id LIMIT 1';
        $stmt = $db->prepare($sql);
            $stmt->execute([
                ':user_id' => $id
            ]);
        $offre = $stmt->fetch(PDO::FETCH_ASSOC);
        if(isset($offre)){
            return $offre;
        }
        return false;
    }

}


