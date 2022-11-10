<?php

namespace sae\web\action;

use sae\web\authentification\Authentification;

class ActivationAction extends Action
{
    public function execute(): string
    {
        $html = "";

        // vérification de la durée d'expiration
        if ($_GET['expire'] > time()) {
            if ($_GET['token'] == $_SESSION['token']) {
                if (Authentification::validation($_GET['email'])) {
                    $html .= <<<HTML
                    <html>                       
                        <h1>      
                            <p>Compte correctement crée</p>
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
                }
            } else {
                $html .= <<<HTML
                    <html>                       
                        <h1>      
                            <p>Erreur durant la validation</p>
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
            }
        } else {
            $html .= <<<HTML
                    <html>                       
                        <h1>      
                            <p>Erreur durant la validation, trop de temps pris</p>
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
        }
        return $html;
    }
}