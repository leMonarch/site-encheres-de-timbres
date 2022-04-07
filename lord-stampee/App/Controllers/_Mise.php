<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Mise extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Home/index.html');
    }


    public function miseAction()
    {
        
        if(empty($_POST)){   
            \App\Models\Mise::update($_POST);
            $miseActuelle = \App\Models\Mise::getMiseActuelleCurrentUser($_POST);
            $oCurrentUserTimbres = \App\Models\Enchere::getAllEnchereFromUser();
            $oPays = \App\Models\Pays::getAllPays();
            $oCertifications = \App\Models\Certification::getAllCertification();
            $oConditions = \App\Models\Condition::getAllCondition();
            View::renderTemplate('Enchere/update.html',[
                'mise' => $miseActuelle,
                'timbres' => $oCurrentUserTimbres,
                'oPays' => $oPays,
                'certifications' => $oCertifications,
                'conditions' => $oConditions,
                'current_user' => $_SESSION['current_user'],
                'firstname' => $_SESSION['current_user']['user_firstname'],
                'lastname' => $_SESSION['current_user']['user_lastname']
            ]);
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

}
