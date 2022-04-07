<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
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
    public static function getAllFromCurrentUser($data)
    {
        $db = static::getDB();
        $sql = 'SELECT * FROM users WHERE user_email = :user_email AND user_mdp = SHA2(:user_mdp, 512)';
        $stmt = $db->prepare($sql);
            $stmt->execute([
                ':user_email' => $data['user_email'],
                ':user_mdp' => $data['user_mdp']
            ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAllFromUserByEmail($data)
    {
        $db = static::getDB();
        if(isset($data['user_email'])){
            $sql = 'SELECT * FROM users WHERE user_email = :user_email';
            $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':user_email' => $data['user_email']
                ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    public static function insert($data)
    {
        $msg = [];
        $email = trim(strtolower($data['user_email']));
        $regExp = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,14}$/i';
        if (!preg_match($regExp, $data['user_mdp']) && $data['user_mdp'] !== $data['user_mdp_verif'] && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg['mdp'] = 'Mot de passe ne correspondent pas et il doit contenir entre 8 et 14 caracteres dont 1 minuscule, 1 majuscule, 1 chiffre et 1 caractere special.';
            $msg['email'] = '';
            return $msg;
        } else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && $data['user_mdp'] === $data['user_mdp_verif']){
            $msg['mdp'] = '';
            $msg['email'] = 'Il faut un format email.';
            return $msg;
        } else if(!preg_match($regExp, $data['user_mdp']) && $data['user_mdp'] !== $data['user_mdp_verif'] && !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $msg['mdp'] = 'Mot de passe ne correspondent pas et il doit contenir entre 8 et 14 caracteres dont 1 minuscule, 1 majuscule, 1 chiffre et 1 caractere special.';
            $msg['email'] = 'Il faut un format email.';
            return $msg;
        } else if($data['user_mdp'] === $data['user_mdp_verif'] && !preg_match($regExp, $data['user_mdp']) && filter_var($email, FILTER_VALIDATE_EMAIL)){
            $msg['mdp'] = 'Mot de passe correspondent mais n\'ont pas entre 8 et 14 caracteres dont 1 minuscule, 1 majuscule, 1 chiffre et 1 caractere special.';
            $msg['email'] = '';
            return $msg;
        } else if($data['user_mdp'] === $data['user_mdp_verif'] && !preg_match($regExp, $data['user_mdp']) && !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $msg['mdp'] = 'Mot de passe correspondent mais n\'ont pas entre 8 et 14 caracteres dont 1 minuscule, 1 majuscule, 1 chiffre et 1 caractere special.';
            $msg['email'] = 'Il faut un format email.';
            return $msg;
        }
        else {
            if($data['user_mdp'] === $data['user_mdp_verif'] && filter_var($email, FILTER_VALIDATE_EMAIL)){
                $db = static::getDB();
                $sql = "INSERT INTO users(user_firstname, user_lastname, user_email,user_mdp) VALUES (:user_firstname, :user_lastname, :user_email, SHA2(:user_mdp, 512))";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':user_firstname' => $data['user_firstname'],
                    ':user_lastname' => $data['user_lastname'],
                    ':user_email' => $data['user_email'],
                    ':user_mdp' => $data['user_mdp']
                ]);
                return 0;
            } else {
                $msg['mdp_non_id'] = 'Mot de passe non identique.';
                return $msg;
            }
        }
        
    }

    public static function update($data)
    {
        if(isset($data['status_id'])) {
            $db = static::getDB();
            $sql = 'UPDATE users SET user_status = :user_status
                                    WHERE user_email = :user_email';
            $stmt = $db->prepare($sql);
        
            if($data['status_id'] != 1){
                $stmt->execute([
                ':user_email' => $data['user_email'],
                ':user_status' => $data['status_id']
                ]); 
                return true;
            }
        }
        return false;
    }


}
