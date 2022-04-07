<?php

namespace App\Controllers;

use Core\Secure;
use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class User extends \Core\Controller
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
        $_SESSION['current_user'] = null;

        //rediriger vers la page principale avec le User connecter
        
        
        if(!empty($_POST)){   
            if($_POST['user_firstname'] !== '' && $_POST['user_lastname'] !== ''){
                $msg= null;
                $user_exist = \App\Models\User::getAllFromCurrentUser($_POST); 
                if(!$user_exist){
                    $msg = \App\Models\User::insert($_POST);
                    if($msg === 0){   
                        //rediriger vers la page principale avec le User connecter
                        $_SESSION['current_user'] = \App\Models\User::getAllFromCurrentUser($_POST);  
                        $u = $_SESSION['current_user'];  
                        $_SESSION['firstname'] = $_POST['user_firstname'];
                        $_SESSION['lastname'] = $_POST['user_lastname'];
                        // $_SESSION['fingerPrint'] = md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);     
                        View::renderTemplate('Home/index.html', [
                            'current_user' => $u,
                            'firstname' => $_SESSION['firstname'],
                            'lastname' => $_SESSION['lastname']
                        ]);
                    } else {
                        // Secure::secure();
                        View::renderTemplate('User/add.html', [
                            'message' => $msg,
                            'firstname' => $_POST['user_firstname'],
                            'lastname' => $_POST['user_lastname']
                        ]);
                    } 
                } else {
                    $msg['exist'] = 'Utilisateur existe deja!';
                    // Secure::secure();
                    View::renderTemplate('User/add.html', [
                        'message' => $msg,
                        'firstname' => $_POST['user_firstname'],
                        'lastname' => $_POST['user_lastname']
                    ]);
                }
            } else {
                View::renderTemplate('User/add.html',[
                    'msg_bienvenue' => 'Entrez votre prénom et nom de famille SVP!'
                ]);
            }
        } else if(!$_SESSION['current_user']){
            View::renderTemplate('User/add.html',[
                'msg_bienvenue' => 'Entrez votre prénom et nom de famille!'
            ]);
        } 
    }

    public function connectAction()
    {
        $_SESSION['current_user'] = null;

        //rediriger vers la page principale avec le User connecter
        if(!empty($_POST)){   
            $_SESSION['current_user'] = \App\Models\User::getAllFromCurrentUser($_POST);  
            $u = $_SESSION['current_user'];   
            if($u){
                 // $_SESSION['fingerPrint'] = md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);     
                View::renderTemplate('Home/index.html', [
                    'current_user' => $u,
                    'firstname' => $_SESSION['current_user']['user_firstname'],
                    'lastname' => $_SESSION['current_user']['user_lastname']
                ]);
            } else {
                View::renderTemplate('User/connect.html',[
                    'erreur_connexion' => 'Votre mot de passe ou votre courriel est erroné!'
                ]);
            }
           
        } else if(!$_SESSION['current_user']){
            View::renderTemplate('User/connect.html');
        }
    }

    public function statusAction()
    {
            $oAllStatus = \App\Models\Status::getAllStatus();
            $oCodeUtilisateur = \App\Models\User::update($_POST);
            $user = \App\Models\User::getAllFromUserByEmail($_POST);
        if(!empty($_POST)){   
            
            if($oCodeUtilisateur){
                if($user['user_status'] == 3){
                    View::renderTemplate('Home/index.html',[
                    'message' => 'L\'utilisateur '.$user['user_email'].' est suspendu.',
                    'allstatus' => $oAllStatus,
                    'user' => $user,
                    'current_user' => $_SESSION['current_user'],
                    'firstname' => $_SESSION['current_user']['user_firstname'],
                    'lastname' => $_SESSION['current_user']['user_lastname']
                    ]);
                } else if($user['user_status'] == 2){
                    View::renderTemplate('Home/index.html',[
                    'message' => 'L\'utilisateur '.$user['user_email'].' est activé.',
                    'allstatus' => $oAllStatus,
                    'user' => $user,
                    'current_user' => $_SESSION['current_user'],
                    'firstname' => $_SESSION['current_user']['user_firstname'],
                    'lastname' => $_SESSION['current_user']['user_lastname']
                    ]);
                } 
                
            } else {
                View::renderTemplate('Home/index.html',[
                    'message' => 'Vous devez choisir le type de droit à accorder.',
                    'allstatus' => $oAllStatus,
                    'user' => $user,
                    'current_user' => $_SESSION['current_user'],
                    'firstname' => $_SESSION['current_user']['user_firstname'],
                    'lastname' => $_SESSION['current_user']['user_lastname']
                ]);
            }
            
        } else {
            View::renderTemplate('User/update.html',[
                'allstatus' => $oAllStatus,
                'user' => $user,
                'current_user' => $_SESSION['current_user'],
                'firstname' => $_SESSION['current_user']['user_firstname'],
                'lastname' => $_SESSION['current_user']['user_lastname']
            ]);
        
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
                        'conditions' => $oConditions,
                    ]);
                } else {
                    View::renderTemplate('Home/index.html',[
                        'message' => 'Pas de recherche concluante.',
                        'timbres' => $oUserRecherche,
                        'oPays' => $oPays,
                        'certifications' => $oCertifications,
                        'conditions' => $oConditions,
                    ]);
                }
            }
            
            
        } 
    }



    public function deconnectAction()
    {
        // remove all session variables
        session_unset();
        // destroy the session
        session_destroy();
        unset($_SESSION['current_user']);
        View::renderTemplate('Home/index.html', [
            'current_user' => false
        ]);
    }

    public function updateAction()
    {
        $id = $this->route_params['id'];
        echo "eutdiante $id modifiee";

    }

}
