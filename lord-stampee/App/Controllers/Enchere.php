<?php

namespace App\Controllers;

// use Core\Secure;
use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Enchere extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {   $users = \App\Models\User::getAll();
        View::renderTemplate('User/index.html',[
            'users' => $users
        ]);
    }


    public function addAction()
    {
        if(!empty($_POST)){   
            $msg = \App\Models\Enchere::insert($_POST, $_FILES['image_lien']['name']);
            if(count($msg) === 0){   
                $oTimbre = \App\Models\Enchere::getEnchere($_POST['timbre_nom']);
                $oPays = \App\Models\Pays::getAllPays();
                $oCertifications = \App\Models\Certification::getAllCertification();
                $oConditions = \App\Models\Condition::getAllCondition();
                View::renderTemplate('Home/fiche.html', [
                    'timbre' => $oTimbre,
                    'oPays' => $oPays,
                    'certifications' => $oCertifications,
                    'conditions' => $oConditions,
                    'current_user' => $_SESSION['current_user'],
                    'firstname' => $_SESSION['current_user']['user_firstname'],
                    'lastname' => $_SESSION['current_user']['user_lastname']
                ]);
            } else {
                // Secure::secure();
                $oPays = \App\Models\Pays::getAllPays();
                $oCertifications = \App\Models\Certification::getAllCertification();
                $oConditions = \App\Models\Condition::getAllCondition();
                View::renderTemplate('Enchere/add.html', [
                    'timbre' => $_POST,
                    'message' => $msg,
                    'image_lien' => $_FILES['image_lien'],
                    'oPays' => $oPays,
                    'certifications' => $oCertifications,
                    'conditions' => $oConditions,
                    'current_user' => $_SESSION['current_user'],
                    'firstname' => $_SESSION['current_user']['user_firstname'],
                    'lastname' => $_SESSION['current_user']['user_lastname']
                ]);
            } 
        } else {
            $oPays = \App\Models\Pays::getAllPays();
            $oCertifications = \App\Models\Certification::getAllCertification();
            $oConditions = \App\Models\Condition::getAllCondition();
            View::renderTemplate('Enchere/add.html',[
                'oPays' => $oPays,
                'certifications' => $oCertifications,
                'conditions' => $oConditions,
                'current_user' => $_SESSION['current_user'],
                'firstname' => $_SESSION['current_user']['user_firstname'],
                'lastname' => $_SESSION['current_user']['user_lastname']
            ]);
        } 
    }


    public function updateAction()
    {
        
        if(empty($_POST)){   
            $oCurrentUserTimbres = \App\Models\Enchere::getAllEnchereFromUser();
            $oPays = \App\Models\Pays::getAllPays();
            $oCertifications = \App\Models\Certification::getAllCertification();
            $oConditions = \App\Models\Condition::getAllCondition();
            if($oCurrentUserTimbres){
                View::renderTemplate('Enchere/update.html',[
                    'timbres' => $oCurrentUserTimbres,
                    'oPays' => $oPays,
                    'certifications' => $oCertifications,
                    'conditions' => $oConditions,
                    'current_user' => $_SESSION['current_user'],
                    'firstname' => $_SESSION['current_user']['user_firstname'],
                    'lastname' => $_SESSION['current_user']['user_lastname']
                ]);
            } else {
                View::renderTemplate('Home/index.html',[
                    'message' => 'Aucune enchère à modifier.',
                    'timbres' => $oCurrentUserTimbres,
                    'oPays' => $oPays,
                    'certifications' => $oCertifications,
                    'conditions' => $oConditions,
                    'current_user' => $_SESSION['current_user'],
                    'firstname' => $_SESSION['current_user']['user_firstname'],
                    'lastname' => $_SESSION['current_user']['user_lastname']
                ]);
            }
            
        } else {
            $msg = \App\Models\Enchere::update($_POST, $_FILES['image_lien']['name']);
            $oCurrentUserTimbres = \App\Models\Enchere::getAllEnchereFromUser();
            $oPays = \App\Models\Pays::getAllPays();
            $oCertifications = \App\Models\Certification::getAllCertification();
            $oConditions = \App\Models\Condition::getAllCondition();
            if(count($msg) === 0){
                 View::renderTemplate('Enchere/update.html',[
                    'timbres' => $oCurrentUserTimbres,
                    'image_lien' => $_FILES['image_lien'],
                    'oPays' => $oPays,
                    'certifications' => $oCertifications,
                    'conditions' => $oConditions,
                    'current_user' => $_SESSION['current_user'],
                    'firstname' => $_SESSION['current_user']['user_firstname'],
                    'lastname' => $_SESSION['current_user']['user_lastname']
                ]);
            } else {
                View::renderTemplate('Enchere/update.html',[
                    'timbres' => $oCurrentUserTimbres,
                    'message' => $msg,
                    'oPays' => $oPays,
                    'certifications' => $oCertifications,
                    'conditions' => $oConditions,
                    'image_lien' => $_FILES['image_lien'],
                    'current_user' => $_SESSION['current_user'],
                    'firstname' => $_SESSION['current_user']['user_firstname'],
                    'lastname' => $_SESSION['current_user']['user_lastname']
                ]);
            }
        } 

    }


    public function deleteAction()
    {
        if(empty($_POST)){   
            $oCurrentUserTimbres = \App\Models\Enchere::getAllEnchereFromUser();
            if($oCurrentUserTimbres){
                View::renderTemplate('Enchere/delete.html', [
                    'timbres' => $oCurrentUserTimbres,
                    'current_user' => $_SESSION['current_user'],
                    'firstname' => $_SESSION['current_user']['user_firstname'],
                    'lastname' => $_SESSION['current_user']['user_lastname']
                ]);
            } else {
                    View::renderTemplate('Home/index.html', [
                        'message' => 'Pas d\'enchère à effacer.',
                        'current_user' => $_SESSION['current_user'],
                        'firstname' => $_SESSION['current_user']['user_firstname'],
                        'lastname' => $_SESSION['current_user']['user_lastname']
                    ]);
            }
        } else {
            $oTimbreDeleted = \App\Models\Enchere::deleteEnchere($_POST);
            $oCurrentUserTimbres = \App\Models\Enchere::getAllEnchereFromUser();
            if($oCurrentUserTimbres){
                 View::renderTemplate('Enchere/delete.html',[
                    'timbres' => $oCurrentUserTimbres,
                    'current_user' => $_SESSION['current_user'],
                    'firstname' => $_SESSION['current_user']['user_firstname'],
                    'lastname' => $_SESSION['current_user']['user_lastname']
                ]);
            } else {
                View::renderTemplate('Home/index.html',[
                    'message' => 'Il n\'y a plus d\'enchère à supprimer.',
                    'current_user' => $_SESSION['current_user'],
                    'firstname' => $_SESSION['current_user']['user_firstname'],
                    'lastname' => $_SESSION['current_user']['user_lastname']
                ]);
            }
        } 
    }

    public function rechercheAction()
    {
        if(!empty($_POST)){   
            $oUserRecherche = \App\Models\Enchere::getAllEnchereRecherche($_POST);
            $oPays = \App\Models\Pays::getAllPays();
            $oCertifications = \App\Models\Certification::getAllCertification();
            $encherePrixMin = \App\Models\Enchere::getPrixMinimum();
            $encherePrixMax = \App\Models\Enchere::getPrixMaximum();
            $oConditions = \App\Models\Condition::getAllCondition();
            if(isset($_SESSION['current_user'])){
                if($oUserRecherche){
                    View::renderTemplate('Home/catalogue.html',[
                        'timbres' => $oUserRecherche,
                        'prixmin' => $encherePrixMin,
                        'prixmax' => $encherePrixMax,
                        'oPays' => $oPays,
                        'certifications' => $oCertifications,
                        'conditions' => $oConditions,
                        'current_user' => $_SESSION['current_user'],
                        'firstname' => $_SESSION['current_user']['user_firstname'],
                        'lastname' => $_SESSION['current_user']['user_lastname']
                    ]);
                } else {
                    View::renderTemplate('Home/index.html',[
                        'message' => 'Pas de recherche concluante.',
                        'timbres' => $oUserRecherche,
                        'oPays' => $oPays,
                        'certifications' => $oCertifications,
                        'conditions' => $oConditions,
                        'current_user' => $_SESSION['current_user'],
                        'firstname' => $_SESSION['current_user']['user_firstname'],
                        'lastname' => $_SESSION['current_user']['user_lastname']
                    ]);
                }
            } else {
                if($oUserRecherche){
                    View::renderTemplate('Home/catalogue.html',[
                        'timbres' => $oUserRecherche,
                        'prixmin' => $encherePrixMin,
                        'prixmax' => $encherePrixMax,
                        'oPays' => $oPays,
                        'certifications' => $oCertifications,
                        'conditions' => $oConditions
                    ]);
                } else {
                    View::renderTemplate('Home/index.html',[
                        'message' => 'Pas de recherche concluante.',
                        'timbres' => $oUserRecherche,
                        'prixmin' => $encherePrixMin,
                        'prixmax' => $encherePrixMax,
                        'oPays' => $oPays,
                        'certifications' => $oCertifications,
                        'conditions' => $oConditions,
                    ]);
                }
            }
            
            
        } 
    }


    // public function connectAction()
    // {
    //     $_SESSION['current_user'] = null;

    //     //rediriger vers la page principale avec le User connecter
    //     if(!empty($_POST)){   
    //         $_SESSION['current_user'] = \App\Models\User::getAllFromCurrentUser($_POST);  
    //         $u = $_SESSION['current_user'];   
    //         if($u){
    //              // $_SESSION['fingerPrint'] = md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);     
    //             View::renderTemplate('Home/index.html', [
    //                 'current_user' => $u,
    //                 'firstname' => $_SESSION['current_user']['user_firstname'],
    //                 'lastname' => $_SESSION['current_user']['user_lastname']
    //             ]);
    //         } else {
    //             View::renderTemplate('User/connect.html',[
    //                 'erreur_connexion' => 'Votre mot de passe ou votre courriel est erroné!'
    //             ]);
    //         }
           
    //     } else if(!$_SESSION['current_user']){
    //         View::renderTemplate('User/connect.html');
    //     }
    // }

    // public function deconnectAction()
    // {
    //     session_start();
    //     // remove all session variables
    //     session_unset();
    //     // destroy the session
    //     session_destroy();
    //     unset($_SESSION['current_user']);
    //     View::renderTemplate('Home/index.html', [
    //         'current_user' => false
    //     ]);
    // }

    

}
