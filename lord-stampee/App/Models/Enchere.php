<?php

namespace App\Models;

use Exception;
use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Enchere extends \Core\Model
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

    public static function insert($data,$file)
    {
        $enchereId = null;
        $msg = [];
        
        //4) encheres 
        //prix plancher
        if (!preg_match('/^\d+$/', $data['enchere_prix_plancher']) || !($data['enchere_prix_plancher'] >= 0)) {
            $msg['prix_plancher'] = 'Entrez un prix supérieur ou égale à 0.';
        }
        //annee debut
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['enchere_date_debut']) || ($data['enchere_date_debut'] >= date("NOW"))) {
            $msg['date_debut'] = 'Entrez la date d\'aujourd\'hui ou une date future.';
        }
        //annee fin
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['enchere_date_fin']) || ($data['enchere_date_debut'] > $data['enchere_date_fin'] )) {
            $msg['date_fin'] = 'Entrez une date de fin supérieure ou égale à la date de début.';
        }
     
        //5) timbres
        $regExp = '/^.+$/';
        if (!preg_match($regExp, $data['timbre_nom'])) {
            $msg['timbre_nom'] = 'Entrez au moins un caractère';
        }
        $regExp = '/^.+$/';
        if (!preg_match($regExp, $data['timbre_description'])) {
            $msg['timbre_description'] = 'Entrez au moins un caractère';
        }
        $regExp = '/^.+$/';
        if (!preg_match($regExp, $data['timbre_type'])) {
            $msg['timbre_type'] = 'Entrez au moins un caractère';
        }
        $regExp = '/^.+$/';
        if (!preg_match($regExp, $data['timbre_dimensions'])) {
            $msg['timbre_dimension'] = 'Entrez au moins un caractère';
        }
        $regExp = '/^.+$/';
        if (!preg_match($regExp, $data['timbre_couleur'])) {
            $msg['timbre_couleur'] = 'Entrez au moins un caractère';
        }
        if (!preg_match('/^\d{4}$/', $data['timbre_annee']) || !($data['timbre_annee'] <= date("Y"))) {
            $msg['timbre_annee'] = 'Entrez une date inférieure ou égale à aujourd\'hui.';
        }
       //6) images lien
        $regExp = '/^(.+\.jpg)$/';
        if (!preg_match($regExp, $file)) {
            $msg['timbre_format'] = 'Entrez le bon format d\'image: .jpg';
        }
        
        $timbreId = null;
       
        if(count($msg) === 0){
            $db = static::getDB();
           
            $sql = "INSERT INTO encheres(enchere_prix_plancher, enchere_prix_actuel, enchere_date_debut, enchere_date_fin, 
                                enchere_categorie, Users_user_id, Users_user_firstname) 
                        VALUES (:enchere_prix_plancher, :enchere_prix_actuel, :enchere_date_debut, :enchere_date_fin,
                        :enchere_categorie, :Users_user_id, :Users_user_firstname)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':enchere_prix_plancher' => $data['enchere_prix_plancher'],
                ':enchere_prix_actuel' => $data['enchere_prix_plancher'],
                ':enchere_date_debut' => $data['enchere_date_debut'],
                ':enchere_date_fin' => $data['enchere_date_fin'],
                ':enchere_categorie' => 'Encheres',
                ':Users_user_id' => $_SESSION['current_user']['user_id'],
                ':Users_user_firstname' => $_SESSION['current_user']['user_firstname']
            ]);
            $enchereId = $db->lastInsertId();
            $sql = "INSERT INTO timbres(timbre_nom, timbre_description, timbre_annee, timbre_type, timbre_dimensions, timbre_couleur,
                                        Pays_pays_id, Conditions_condition_id, Certifications_certification_id, Encheres_enchere_id) 
                        VALUES (:timbre_nom, :timbre_description, :timbre_annee, :timbre_type, :timbre_dimensions, :timbre_couleur,
                                :Pays_pays_id, :Conditions_condition_id, :Certifications_certification_id, :Encheres_enchere_id)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':timbre_nom' => $data['timbre_nom'],
                ':timbre_description' => $data['timbre_description'],
                ':timbre_annee' => $data['timbre_annee'],
                ':timbre_type' => $data['timbre_type'],
                ':timbre_dimensions' => $data['timbre_dimensions'],
                ':timbre_couleur' => $data['timbre_couleur'],
                ':Pays_pays_id' => $data['Pays_pays_id'],
                ':Conditions_condition_id' => $data['Conditions_condition_id'],
                ':Certifications_certification_id' => $data['Certifications_certification_id'],
                ':Encheres_enchere_id' => $enchereId,
            ]);
            $timbreId = $db->lastInsertId();
            $sql = "INSERT INTO images (image_lien, Timbres_timbre_id, Timbres_Pays_pays_id, Timbres_Conditions_condition_id, Timbres_Certifications_certification_id,
                                        Timbres_Encheres_enchere_id) 
                        VALUES (:image_lien, :Timbres_timbre_id, :Timbres_Pays_pays_id, :Timbres_Conditions_condition_id, :Timbres_Certifications_certification_id,
                                        :Timbres_Encheres_enchere_id)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':image_lien' => $file,
                ':Timbres_timbre_id' => $timbreId,
                ':Timbres_Pays_pays_id' => $data['Pays_pays_id'],
                ':Timbres_Conditions_condition_id' => $data['Conditions_condition_id'],
                ':Timbres_Certifications_certification_id' => $data['Certifications_certification_id'],
                ':Timbres_Encheres_enchere_id' => $enchereId
            ]);
            $imageId = $db->lastInsertId();

            if(!@move_uploaded_file($_FILES['image_lien']['tmp_name'], 'img/timbre-'.$imageId.'.jpg'))
            {
                $msg['image_lien'] = "Sorry, there was a problem uploading your file.";
                throw new Exception("Le stockage du fichier image de du film a échoué.");
            }
            $sql = 'UPDATE images SET image_lien = :image_lien WHERE image_id = :image_id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':image_lien' => 'https://e1224194.webdev.cmaisonneuve.qc.ca/public/img/timbre-'.$imageId.'.jpg',
                ':image_id' => $imageId
            ]);
        }
        
        return $msg;
    }

    public static function update($data, $file)
    {
   
        $msg = [];
        
        //4) encheres 
        //prix plancher
        if (!preg_match('/^\d+$/', $data['enchere_prix_plancher']) || !($data['enchere_prix_plancher'] >= 0)) {
            $msg['prix_plancher'] = 'Entrez un prix supérieur ou égale à 0.';
        }
       //annee debut
       if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['enchere_date_debut']) || ($data['enchere_date_debut'] < date("NOW"))) {
        $msg['date_debut'] = 'Entrez la date d\'aujourd\'hui ou une date future.';
        }
        //annee fin
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['enchere_date_fin']) || ($data['enchere_date_debut'] > $data['enchere_date_fin'] )) {
            $msg['date_fin'] = 'Entrez une date de fin supérieure ou égale à la date de début.';
        }
        //5) timbres
        $regExp = '/^.+$/';
        if (!preg_match($regExp, $data['timbre_nom'])) {
            $msg['timbre_nom'] = 'Entrez au moins un caractère';
        }
        $regExp = '/^.+$/';
        if (!preg_match($regExp, $data['timbre_description'])) {
            $msg['timbre_description'] = 'Entrez au moins un caractère';
        }
        $regExp = '/^.+$/';
        if (!preg_match($regExp, $data['timbre_type'])) {
            $msg['timbre_type'] = 'Entrez au moins un caractère';
        }
        $regExp = '/^.+$/';
        if (!preg_match($regExp, $data['timbre_dimensions'])) {
            $msg['timbre_dimension'] = 'Entrez au moins un caractère';
        }
        $regExp = '/^.+$/';
        if (!preg_match($regExp, $data['timbre_couleur'])) {
            $msg['timbre_couleur'] = 'Entrez au moins un caractère';
        }
        if (!preg_match('/^\d{4}$/', $data['timbre_annee']) || !($data['timbre_annee'] <= date("Y"))) {
            $msg['timbre_annee'] = 'Entrez une date inférieure ou égale à aujourd\'hui.';
        }
       //6) images lien
        $regExp = '/^(.+\.jpg)$/';
        
        if($file){
            if (!preg_match($regExp, $file)) {
                $msg['timbre_format'] = $file.'Entrez le bon format d\'image: .jpg';
            }
        }
        
        if(count($msg)===0){
            $db = static::getDB();
            $sql = 'UPDATE encheres SET enchere_prix_plancher = :enchere_prix_plancher, 
                                        enchere_date_debut = :enchere_date_debut, 
                                        enchere_date_fin = :enchere_date_fin
                                    WHERE enchere_id = :enchere_id';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':enchere_prix_plancher' => $data['enchere_prix_plancher'],
                ':enchere_date_debut' => $data['enchere_date_debut'],
                ':enchere_date_fin' => $data['enchere_date_fin'],
                ':enchere_id' => $data['Encheres_enchere_id']
            ]);
            if($file){
                $sql = 'DELETE FROM images WHERE image_id = :image_id';
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':image_id' => $data['image_id']
                ]);
                $sql = "INSERT INTO images (image_lien, Timbres_timbre_id, Timbres_Pays_pays_id, Timbres_Conditions_condition_id, 
                                            Timbres_Certifications_certification_id, Timbres_Encheres_enchere_id) 
                VALUES (:image_lien, :Timbres_timbre_id, :Timbres_Pays_pays_id, :Timbres_Conditions_condition_id, :Timbres_Certifications_certification_id,
                        :Timbres_Encheres_enchere_id)";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':image_lien' => 'https://e1224194.webdev.cmaisonneuve.qc.ca/public/img/timbre-'.$data['image_id'].'.jpg',
                    ':Timbres_timbre_id' => $data['timbre_id'],
                    ':Timbres_Pays_pays_id' => $data['Pays_pays_id'],
                    ':Timbres_Conditions_condition_id' => $data['Conditions_condition_id'],
                    ':Timbres_Certifications_certification_id' => $data['Certifications_certification_id'],
                    ':Timbres_Encheres_enchere_id' => $data['Encheres_enchere_id']
                ]);
                $imageId = $db->lastInsertId();
                // // $imageId = $data['image_id'];
                // // echo 'img:'.$imageId;
        
                if(!@move_uploaded_file($_FILES['image_lien']['tmp_name'], 'img/timbre-'.$imageId.'.jpg'))
                {
                    $msg['image_lien'] = "Sorry, there was a problem uploading your file.";
                    throw new Exception("Le stockage du fichier image de du timbre a échoué.");
                }
                unlink('img/timbre-'.$data['image_id'].'.jpg');

                $sql = 'UPDATE images SET image_lien = :image_lien WHERE image_id = :image_id';
                $stmt = $db->prepare($sql);
                $stmt->execute([
                        ':image_lien' => 'https://e1224194.webdev.cmaisonneuve.qc.ca/public/img/timbre-'.$imageId.'.jpg',
                        ':image_id' => $imageId
                    ]);
            } else {
                $sql = 'UPDATE encheres SET enchere_prix_plancher = :enchere_prix_plancher, 
                                        enchere_date_debut = :enchere_date_debut, 
                                        enchere_date_fin = :enchere_date_fin
                                    WHERE enchere_id = :enchere_id';
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':enchere_prix_plancher' => $data['enchere_prix_plancher'],
                    ':enchere_date_debut' => $data['enchere_date_debut'],
                    ':enchere_date_fin' => $data['enchere_date_fin'],
                    ':enchere_id' => $data['Encheres_enchere_id']
                ]);
                $sql = 'UPDATE timbres SET timbre_nom = :timbre_nom, 
                                        timbre_description = :timbre_description, 
                                        timbre_annee = :timbre_annee,
                                        timbre_type = :timbre_type, 
                                        timbre_dimensions = :timbre_dimensions,
                                        timbre_couleur = :timbre_couleur,
                                        Pays_pays_id = :Pays_pays_id, 
                                        Conditions_condition_id = :Conditions_condition_id,
                                        Certifications_certification_id = :Certifications_certification_id,
                                        Encheres_enchere_id = :Encheres_enchere_id
                                                                WHERE timbre_id = :timbre_id';
                                        $stmt = $db->prepare($sql);
                                        $stmt->execute([
                                            ':timbre_nom' => $data['timbre_nom'],
                                            ':timbre_description' => $data['timbre_description'],
                                            ':timbre_annee' => $data['timbre_annee'],
                                            ':timbre_type' => $data['timbre_type'],
                                            ':timbre_dimensions' => $data['timbre_dimensions'],
                                            ':timbre_couleur' => $data['timbre_couleur'],
                                            ':Pays_pays_id' => $data['Pays_pays_id'],
                                            ':Conditions_condition_id' => $data['Conditions_condition_id'],
                                            ':Certifications_certification_id' => $data['Certifications_certification_id'],
                                            ':Encheres_enchere_id' => $data['Encheres_enchere_id'],
                                            ':timbre_id' => $data['timbre_id']
                                        ]);
                $sql = 'UPDATE images SET Timbres_timbre_id = :Timbres_timbre_id, Timbres_Pays_pays_id = :Timbres_Pays_pays_id,
                Timbres_Conditions_condition_id = :Timbres_Conditions_condition_id, 
                Timbres_Certifications_certification_id = :Timbres_Certifications_certification_id,
                Timbres_Encheres_enchere_id = :Timbres_Encheres_enchere_id
                                    WHERE image_id = :image_id';
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute([
                                    ':Timbres_timbre_id' => $data['timbre_id'],
                                    ':Timbres_Pays_pays_id' => $data['Pays_pays_id'],
                                    ':Timbres_Conditions_condition_id' => $data['Conditions_condition_id'],
                                    ':Timbres_Certifications_certification_id' => $data['Certifications_certification_id'],
                                    ':Timbres_Encheres_enchere_id' => $data['Encheres_enchere_id'],
                                    ':image_id' => $data['image_id']
                                    ]);
            }
            
            
           
            
        }
        return $msg;
    }

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getEnchere($timbreNom)
    {
        $db = static::getDB();
        $sql = 'SELECT timbre_id, timbre_nom, timbre_description, timbre_annee, timbre_type, timbre_dimensions, timbre_couleur, 
                        certification_id, certification_nom, condition_id, condition_nom, pays_id, pays_nom, 
                        enchere_id, enchere_prix_plancher, enchere_prix_actuel, enchere_date_debut, enchere_date_fin, image_id, image_lien 
                        FROM timbres 
                        INNER JOIN certifications ON certification_id = Certifications_certification_id 
                        INNER JOIN conditions ON condition_id = Conditions_condition_id 
                        INNER JOIN pays ON pays_id = Pays_pays_id 
                        INNER JOIN encheres ON enchere_id = Encheres_enchere_id 
                        INNER JOIN images ON timbre_id = Timbres_timbre_id 
                        WHERE timbre_nom = :timbre_nom;';
        $stmt = $db->prepare($sql);
            $stmt->execute([
                ':timbre_nom' => $timbreNom,
            ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getEnchereById($enchereId)
    {
        $db = static::getDB();
        $sql = 'SELECT timbre_id, timbre_nom, timbre_description, timbre_annee, timbre_type, timbre_dimensions, timbre_couleur, 
                        certification_id, certification_nom, condition_id, condition_nom, pays_id, pays_nom, 
                        enchere_id, enchere_prix_plancher, enchere_date_debut, enchere_date_fin, image_id, image_lien 
                        FROM timbres 
                        INNER JOIN certifications ON certification_id = Certifications_certification_id 
                        INNER JOIN conditions ON condition_id = Conditions_condition_id 
                        INNER JOIN pays ON pays_id = Pays_pays_id 
                        INNER JOIN encheres ON enchere_id = Encheres_enchere_id 
                        INNER JOIN images ON timbre_id = Timbres_timbre_id 
                        WHERE enchere_id = :enchere_id;';
        $stmt = $db->prepare($sql);
            $stmt->execute([
                ':enchere_id' => $enchereId,
            ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Get all the encheres from a user as an associative array
     *
     * @return array
     */
    public static function getAllEnchereFromUser()
    {
        $db = static::getDB();
        $sql = 'SELECT timbre_id, timbre_nom, timbre_description, timbre_annee, timbre_type, timbre_dimensions, timbre_couleur, 
                        Certifications_certification_id, Conditions_condition_id, Pays_pays_id, Encheres_enchere_id
                        certification_id, certification_nom, condition_id, condition_nom, pays_id, pays_nom, 
                        enchere_id, enchere_prix_plancher, enchere_prix_actuel, enchere_date_debut, enchere_date_fin, 
                        Users_user_id, Users_user_firstname, image_id, image_lien 
                        FROM timbres 
                        INNER JOIN certifications ON certification_id = Certifications_certification_id 
                        INNER JOIN conditions ON condition_id = Conditions_condition_id 
                        INNER JOIN pays ON pays_id = Pays_pays_id 
                        INNER JOIN encheres ON enchere_id = Encheres_enchere_id 
                        INNER JOIN images ON timbre_id = Timbres_timbre_id 
                        WHERE Users_user_id = :Users_user_id;';
        $stmt = $db->prepare($sql);
            $stmt->execute([
                ':Users_user_id' => $_SESSION['current_user']['user_id'],
            ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all the encheres from a user as an associative array
     *
     * @return array
     */
    public static function getAllEnchere()
    {
        $db = static::getDB();
        $sql = 'SELECT timbre_id, timbre_nom, timbre_description, timbre_annee, timbre_type, timbre_dimensions, timbre_couleur, 
                        Certifications_certification_id, Conditions_condition_id, Pays_pays_id, Encheres_enchere_id,
                        certification_id, certification_nom, condition_id, condition_nom, pays_id, pays_nom, 
                        enchere_id, gagnant_nom, enchere_prix_plancher, enchere_prix_actuel, enchere_date_debut, enchere_date_fin,
                        Users_user_id, Users_user_firstname, image_id, image_lien 
                        FROM timbres 
                        INNER JOIN certifications ON certification_id = Certifications_certification_id 
                        INNER JOIN conditions ON condition_id = Conditions_condition_id 
                        INNER JOIN pays ON pays_id = Pays_pays_id 
                        INNER JOIN encheres ON enchere_id = Encheres_enchere_id 
                        INNER JOIN images ON timbre_id = Timbres_timbre_id';
        $stmt = $db->prepare($sql);
            $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all the encheres from a user as an associative array
     *
     * @return array
     */
    public static function getAllEnchereRecherche($data)
    {
        $db = static::getDB();
        $sql = 'SELECT timbre_id, timbre_nom, timbre_description, timbre_annee, timbre_type, timbre_dimensions, timbre_couleur, 
                        Certifications_certification_id, Conditions_condition_id, Pays_pays_id, Encheres_enchere_id,
                        certification_id, certification_nom, condition_id, condition_nom, pays_id, pays_nom, 
                        enchere_id, gagnant_nom, enchere_prix_plancher, enchere_prix_actuel, enchere_date_debut, enchere_date_fin,
                        Users_user_id, Users_user_firstname, image_id, image_lien 
                        FROM timbres 
                        INNER JOIN certifications ON certification_id = Certifications_certification_id 
                        INNER JOIN conditions ON condition_id = Conditions_condition_id 
                        INNER JOIN pays ON pays_id = Pays_pays_id 
                        INNER JOIN encheres ON enchere_id = Encheres_enchere_id 
                        INNER JOIN images ON timbre_id = Timbres_timbre_id
                        WHERE timbre_nom LIKE :timbre_nom
                        OR timbre_description LIKE :timbre_description
                        OR timbre_annee LIKE :timbre_annee
                        OR timbre_type LIKE :timbre_type
                        OR timbre_dimensions LIKE :timbre_dimensions
                        OR timbre_couleur LIKE :timbre_couleur
                        OR certification_nom LIKE :certification_nom
                        OR condition_nom LIKE :condition_nom
                        OR pays_nom LIKE :pays_nom
                        OR Users_user_firstname LIKE :Users_user_firstname';
        $stmt = $db->prepare($sql);
        if($data['keyword'] <> ''){
            $stmt->execute([
                ':timbre_nom' => '%'.trim($data['keyword']).'%',
                ':timbre_description' => '%'.trim($data['keyword']).'%',
                ':timbre_annee' => '%'.trim($data['keyword']).'%',
                ':timbre_type' => '%'.trim($data['keyword']).'%',
                ':timbre_dimensions' => '%'.trim($data['keyword']).'%',
                ':timbre_couleur' => '%'.trim($data['keyword']).'%',
                ':certification_nom' => '%'.trim($data['keyword']).'%',
                ':condition_nom' => '%'.trim($data['keyword']).'%',
                ':pays_nom' => '%'.trim($data['keyword']).'%',
                ':Users_user_firstname' => '%'.trim($data['keyword']).'%'
                ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
            
    }


    /**
     * Get all the encheres from a user as an associative array
     *
     * @return array
     */
    public static function getAllEnchereRechercheAvancee($data)
    {
        $msg = [];
        //5) timbres
        $regExp = '/^.+$/';
        if (!preg_match($regExp, $data['keyword'])) {
            $msg['keyword'] = 'Entrez au moins un caractère';
        }
        //prix debut
        if ($data['enchere_prix_actuel_min'] >= $data['enchere_prix_actuel_max'] ) {
            $msg['enchere_prix_actuel'] = 'Entrez un prix min inférieur au prix max.';
        }
        //prix fin
        if ($data['timbre_annee_min'] >= $data['timbre_annee_max'] )  {
            $msg['timbre_annee'] = 'Entrez une année min inférieur à l\'année max.';
        }
        $data['menthe'] = $data['utilise'] = $data['inutilise'] = '';
        $data['lord-stampee'] = $data['best-seller'] = '';

       
        if(!empty($data['condition'])){
            for ($i=0, $l = count($data['condition']); $i < $l; $i++) { 
                if($data['condition'][$i]){
                    if($data['condition'][$i] === 'Menthe'){
                        $data['menthe'] = $data['condition'][$i];
                    }
                    if($data['condition'][$i] === 'Utilisé'){
                        $data['utilise'] = $data['condition'][$i];
                    }
                    if($data['condition'][$i] === 'Inutilisé'){
                        $data['inutilise'] = $data['condition'][$i];
                    }
                }
            }
        } else {
            $msg['condition'] = 'Vous devez cocher une case au minimum.';
        }
        if(!empty($data['certification'])){
            for ($i=0, $l = count($data['certification']); $i < $l; $i++) { 
                if($data['certification'][$i]){
                    if($data['certification'][$i] === 'Lord Stampee'){
                        $data['lord-stampee'] = $data['certification'][$i];
                    }
                    if($data['certification'][$i] === 'Best Seller'){
                        $data['best-seller'] = $data['certification'][$i];
                    }
                }
            }
        } else {
            $msg['certification'] = 'Vous devez cocher une case au minimum.';
        }

        if(count($msg) === 0){
            $db = static::getDB();
            $sql = 'SELECT timbre_id, timbre_nom, timbre_description, timbre_annee, timbre_type, timbre_dimensions, timbre_couleur, 
                        Certifications_certification_id, Conditions_condition_id, Pays_pays_id, Encheres_enchere_id
                        certification_id, certification_nom, condition_id, condition_nom, pays_id, pays_nom, 
                        enchere_id, enchere_prix_plancher, enchere_prix_actuel, enchere_date_debut, enchere_categorie, enchere_date_fin, 
                        gagnant_nom, Users_user_id, Users_user_firstname,
                        Timbres_timbre_id, image_id, image_lien 
                        FROM timbres 
                        INNER JOIN certifications ON certification_id = Certifications_certification_id 
                        INNER JOIN conditions ON condition_id = Conditions_condition_id 
                        INNER JOIN pays ON pays_id = Pays_pays_id 
                        INNER JOIN encheres ON enchere_id = Encheres_enchere_id 
                        INNER JOIN images ON timbre_id = Timbres_timbre_id
                        WHERE timbre_nom LIKE :timbre_nom
                        AND enchere_prix_actuel >= :enchere_prix_actuel_min
                        AND enchere_prix_actuel <= :enchere_prix_actuel_max
                        AND timbre_annee >= :timbre_annee_min
                        AND timbre_annee <= :timbre_annee_max
                        OR timbre_couleur LIKE :timbre_couleur
                        AND enchere_categorie = :enchere_categorie
                        AND certification_nom = :certification_nom_lord
                        AND certification_nom = :certification_nom_best
                        AND condition_nom = :condition_nom_menthe
                        AND condition_nom = :condition_nom_utilise
                        AND condition_nom = :condition_nom_inutilise
                        OR pays_nom LIKE :pays_nom
                        OR gagnant_nom LIKE :gagnant_nom
                        OR Users_user_firstname LIKE :Users_user_firstname';
            $stmt = $db->prepare($sql);
                $stmt->execute([
                ':timbre_nom' => '%'.trim($data['keyword']).'%',
                ':enchere_prix_actuel_min' => $data['enchere_prix_actuel_min'],
                ':enchere_prix_actuel_max' => $data['enchere_prix_actuel_max'],
                ':timbre_annee_min' => $data['timbre_annee_min'],
                ':timbre_annee_max' => $data['timbre_annee_max'],
                ':timbre_couleur' => '%'.trim($data['keyword']).'%',
                ':enchere_categorie' => $data['enchere_categorie'],
                ':condition_nom_menthe' => $data['menthe'],
                ':condition_nom_utilise' => $data['utilise'],
                ':condition_nom_inutilise' => $data['inutilise'],
                ':certification_nom_lord' => $data['lord-stampee'],
                ':certification_nom_best' => $data['best-seller'],
                ':pays_nom' => '%'.trim($data['keyword']).'%',
                ':gagnant_nom' => '%'.trim($data['keyword']).'%',
                ':Users_user_firstname' => '%'.trim($data['keyword']).'%'
                // ':timbre_nom' => 'Jean',
                // ':enchere_prix_actuel_min' => '2700',
                // ':enchere_prix_actuel_max' => '2800',
                // ':timbre_annee_min' => '1890',
                // ':timbre_annee_max' => '1910',
                // ':timbre_couleur' => 'Jean',
                // ':enchere_categorie' => 'Encheres',
                // ':condition_nom_menthe' => '',
                // ':condition_nom_utilise' => 'Utilisé',
                // ':condition_nom_inutilise' => '',
                // ':certification_nom_lord' => '',
                // ':certification_nom_best' => 'Best Seller',
                // ':pays_nom' => 'Jean',
                // ':gagnant_nom' => 'Jean',
                // ':Users_user_firstname' => 'Jean'
                ]);
                return [true, $stmt->fetchAll(PDO::FETCH_ASSOC)];
       }
        
       return [false, $msg];
                // return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
    }



    public static function deleteEnchere($data)
    {
        try{
             //Une autre façon est de modéliser la DB avec DELETE ON CASCADE sur timbres puisque c'est le comportement attendu en tout temps
            $db = static::getDB();
            $sql = 'DELETE images FROM images 
                    INNER JOIN timbres ON timbre_id = Timbres_timbre_id 
                    WHERE timbre_id = :timbre_id;';
            $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':timbre_id' => $data['timbre_id'],
                ]);
            $sql = 'DELETE timbres FROM timbres 
                    INNER JOIN certifications ON certification_id = Certifications_certification_id 
                    INNER JOIN conditions ON condition_id = Conditions_condition_id 
                    INNER JOIN pays ON pays_id = Pays_pays_id 
                    INNER JOIN encheres ON enchere_id = Encheres_enchere_id 
                    WHERE timbre_id = :timbre_id;';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':timbre_id' => $data['timbre_id'],
            ]);
            
            $sql = 'DELETE FROM encheres 
            WHERE enchere_id = :enchere_id;';
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':enchere_id' => $data['enchere_id'],
            ]);
            unlink('img/timbre-'.$data['image_id'].'.jpg');
            return true;
        } catch( Exception $e){
            return $e->getMessage();
        }
       
    }

    /**
     * Get all the encheres from a user as an associative array
     *
     * @return array
     */
    public static function getPrixMinimum()
    {
        $db = static::getDB();
        $sql = 'SELECT MIN(enchere_prix_actuel) AS prix_min FROM encheres';
        $stmt = $db->prepare($sql);
            $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all the encheres from a user as an associative array
     *
     * @return array
     */
    public static function getPrixMaximum()
    {
        $db = static::getDB();
        $sql = 'SELECT MAX(enchere_prix_actuel) AS prix_max FROM encheres';
        $stmt = $db->prepare($sql);
            $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
