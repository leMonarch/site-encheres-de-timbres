<?php

namespace App\Controllers;

use \Core\View;
/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        if(isset($_SESSION['current_user'])){
            View::renderTemplate('Home/index.html', [
                'current_user' => $_SESSION['current_user'],
                'firstname' => $_SESSION['current_user']['user_firstname'],
                'lastname' => $_SESSION['current_user']['user_lastname']
            ]);
        } else {
            View::renderTemplate('Home/index.html');
        }
        
    }

    public function rechercheAction()
    {
        if(!empty($_POST)){   
            $oUserRecherche = \App\Models\Enchere::getAllEnchereRecherche($_POST);
            $encherePrixMin = \App\Models\Enchere::getPrixMinimum();
            $encherePrixMax = \App\Models\Enchere::getPrixMaximum();
            $oPays = \App\Models\Pays::getAllPays();
            $oCertifications = \App\Models\Certification::getAllCertification();
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
                        'prixmin' => $encherePrixMin,
                        'prixmax' => $encherePrixMax,
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


    public function rechercheavanceeAction()
    {
        // if(!empty($_POST)){   
        //     $oUserRecherchesAvancees = \App\Models\Enchere::getAllEnchereRecherche($_POST);
        //     if($oUserRecherchesAvancees){
        //         echo json_encode($oUserRecherchesAvancees);
        //     } else {
        //         echo 'Erreur de query';
        //     }
				
        // }	
        if(!empty($_POST)){   
            $oAllEncheres = \App\Models\Enchere::getAllEnchere();
            $oUserRecherche = \App\Models\Enchere::getAllEnchereRechercheAvancee($_POST);
            $encherePrixMin = \App\Models\Enchere::getPrixMinimum();
            $encherePrixMax = \App\Models\Enchere::getPrixMaximum();
            $oPays = \App\Models\Pays::getAllPays();
            $oCertifications = \App\Models\Certification::getAllCertification();
            $oConditions = \App\Models\Condition::getAllCondition();
            if(isset($_SESSION['current_user'])){
                if($oUserRecherche[0]){
                    View::renderTemplate('Home/catalogue.html',[
                        'timbres' => $oUserRecherche[1],
                        'prixmin' => $encherePrixMin,
                        'prixmax' => $encherePrixMax,
                        'oPays' => $oPays,
                        'certifications' => $oCertifications,
                        'conditions' => $oConditions,
                        'current_user' => $_SESSION['current_user'],
                        'firstname' => $_SESSION['current_user']['user_firstname'],
                        'lastname' => $_SESSION['current_user']['user_lastname']
                    ]);
                } else if(count($oUserRecherche[1])!==0) {
                    View::renderTemplate('Home/catalogue.html',[
                        'message' => $oUserRecherche[1],
                        'timbres' => $oAllEncheres,
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
                        'message' => 'Recherche non concluante',
                        'timbres' => $oAllEncheres,
                        'prixmin' => $encherePrixMin,
                        'prixmax' => $encherePrixMax,
                        'oPays' => $oPays,
                        'certifications' => $oCertifications,
                        'conditions' => $oConditions,
                        'current_user' => $_SESSION['current_user'],
                        'firstname' => $_SESSION['current_user']['user_firstname'],
                        'lastname' => $_SESSION['current_user']['user_lastname']
                    ]);
                }
            } else {
                if($oUserRecherche[0]){
                    View::renderTemplate('Home/catalogue.html',[
                        'message' => 'recherche avancée effectuée',
                        'timbres' => $oUserRecherche[1],
                        'prixmin' => $encherePrixMin,
                        'prixmax' => $encherePrixMax,
                        'oPays' => $oPays,
                        'certifications' => $oCertifications,
                        'conditions' => $oConditions,
                    ]);
                } else if(count($oUserRecherche[1])!==0) {
                    View::renderTemplate('Home/catalogue.html',[
                        'message' => $oUserRecherche[1],
                        'timbres' => $oAllEncheres,
                        'prixmin' => $encherePrixMin,
                        'prixmax' => $encherePrixMax,
                        'oPays' => $oPays,
                        'certifications' => $oCertifications,
                        'conditions' => $oConditions,
                        // 'current_user' => $_SESSION['current_user'],
                        // 'firstname' => $_SESSION['current_user']['user_firstname'],
                        // 'lastname' => $_SESSION['current_user']['user_lastname']
                    ]);
                } else {
                    View::renderTemplate('Home/index.html',[
                        'message' => 'Recherche non concluante',
                        'timbres' => $oAllEncheres,
                        'prixmin' => $encherePrixMin,
                        'prixmax' => $encherePrixMax,
                        'oPays' => $oPays,
                        'certifications' => $oCertifications,
                        'conditions' => $oConditions,
                        // 'current_user' => $_SESSION['current_user'],
                        // 'firstname' => $_SESSION['current_user']['user_firstname'],
                        // 'lastname' => $_SESSION['current_user']['user_lastname']
                    ]);
                }
            }
            
            
        } 
    }

    public function catalogueAction()
    {
        $oAllUsersTimbres = \App\Models\Enchere::getAllEnchere();
        $encherePrixMin = \App\Models\Enchere::getPrixMinimum();
        $encherePrixMax = \App\Models\Enchere::getPrixMaximum();
        if(isset($_SESSION['current_user'])){
            View::renderTemplate('Home/catalogue.html',[
                'timbres' => $oAllUsersTimbres,
                'prixmin' => $encherePrixMin,
                'prixmax' => $encherePrixMax,
                'current_user' => $_SESSION['current_user'],
                'firstname' => $_SESSION['current_user']['user_firstname'],
                'lastname' => $_SESSION['current_user']['user_lastname']
            ]);
        } else {
            View::renderTemplate('Home/catalogue.html',[
                'timbres' => $oAllUsersTimbres,
                'prixmin' => $encherePrixMin,
                'prixmax' => $encherePrixMax,
                'current_user' => false,
                'timbres' => $oAllUsersTimbres
            ]);
        }
    }

    public function miseAction()
    {
        if(!isset($_SESSION['current_user'])){
            View::renderTemplate('Home/index.html',[
                'message' => 'Connectez-vous pour miser sur une enchère.',
            ]);
        } else {
            $msg = \App\Models\Mise::update($_POST);
            $id = \App\Models\Mise::getIdGagnantEnListe($_POST);
            $encherePrixMin = \App\Models\Enchere::getPrixMinimum();
            $encherePrixMax = \App\Models\Enchere::getPrixMaximum();
            $gagnant = \App\Models\Mise::getGagnantEnListe($id);
            $oTimbres = \App\Models\Enchere::getAllEnchere();
            if(empty($_POST)){  
                View::renderTemplate('Home/catalogue.html',[
                    'timbres' => $oTimbres,
                    'prixmin' => $encherePrixMin,
                    'prixmax' => $encherePrixMax,
                    'message' => 'Mise actuelle de '.$gagnant['user_firstname'],
                    'current_user' => $_SESSION['current_user'],
                    'firstname' => $_SESSION['current_user']['user_firstname'],
                    'lastname' => $_SESSION['current_user']['user_lastname']
                ]);
            } else {
                if(count($msg) === 0){
                    View::renderTemplate('Home/catalogue.html',[
                        'timbres' => $oTimbres,
                        'prixmin' => $encherePrixMin,
                        'prixmax' => $encherePrixMax,
                        'message' => 'salut',
                        'current_user' => $_SESSION['current_user'],
                        'firstname' => $_SESSION['current_user']['user_firstname'],
                        'lastname' => $_SESSION['current_user']['user_lastname']
                    ]);
                } else {
                    View::renderTemplate('Home/catalogue.html',[
                        'timbres' => $oTimbres,
                        'prixmin' => $encherePrixMin,
                        'prixmax' => $encherePrixMax,
                        'message' => $msg['message'],
                        'current_user' => $_SESSION['current_user'],
                        'firstname' => $_SESSION['current_user']['user_firstname'],
                        'lastname' => $_SESSION['current_user']['user_lastname']
                    ]);
                }
            }
        }
    }
}
