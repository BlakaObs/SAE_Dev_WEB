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
                    $html .= "<p>Compte correctement crée</p>";
                    $html .= "<br><a href='index.php'>Retour à l'accueil</a>";
                }
            } else {
                Authentification::suppression($_GET['email']);
                $html .= "<p>Erreur durant la validation</p>";
                $html .= "<br><a href='index.php'>Retour à l'accueil</a>";
            }
        } else {
            Authentification::suppression($_GET['email']);
            $html .= "<p>Erreur durant la validation, trop de temps pris.</p>";
            $html .= "<br><a href='index.php'>Retour à l'accueil</a>";
        }
        return $html;
    }
}