<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AjoutPreferencesAction extends Action
{

    public function execute(): string
    {
        $html = "";

        // connexion à la BDD
        $bd = ConnectionFactory::makeConnection();

        // requête pour mettre à jour les favoris
        $query = $bd->prepare("UPDATE seriePref SET pref = 1 WHERE email = ? AND serie_id = ? ");
        $email = $_SESSION['user'];
        $query->bindParam(1, $email);
        $query->bindParam(2, $_GET['id']);

        $query->execute();
        $html = <<<HTML
                    <html>                       
                        <h1>      
                            <p>Ajout de la série à vos préférence effectué</p>
                        </h1> 
                            <div class="div4">
                                <div>
                                    <h3>
                                        <a href='index.php'>Retour a l'accueil</a>
                                    </h3>
                                </div>             
                    </html>
                    <link rel="stylesheet" href="css/connexion.css" type="text/css" />
                    HTML;
        return $html;
    }
}