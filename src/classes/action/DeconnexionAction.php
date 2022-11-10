<?php

namespace sae\web\action;

class DeconnexionAction extends Action
{
    public function execute(): string
    {

        // destruction de la session (deconnexion)
        session_destroy();
        $html = <<<HTML
                    <html>                       
                        <h1>      
                            <p>Vous avez été déconnecté</p>
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