<?php

namespace App\Models;
namespace App\Controllers;
echo 'test';

switch($_POST['action']){
    case 'rechercheavancee':
                if(!empty($_POST)){   
                    echo 'test';
                
                    // require_once('../../../Core/Model.php');
                    $oUserRecherchesAvancees = \App\Models\Enchere::getAllEnchereRechercheAvancee($_POST);
                    //     echo json_encode($oUserRecherchesAvancees );
                } else {
                        echo 'Erreur query string';
                }
        break;
}
    