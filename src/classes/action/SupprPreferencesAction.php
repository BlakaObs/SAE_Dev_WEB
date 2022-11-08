<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class SupprPreferencesAction extends Action
{

    public function execute(): string
    {
        $html = "";
        $bd = ConnectionFactory::makeConnection();
        $query = $bd->prepare("UPDATE seriePref SET pref = 0 WHERE email = ? AND serie_id = ? ");
        $email = $_SESSION['user'];
        $query->bindParam(1, $email);
        $query->bindParam(2, $_GET['id']);

        $query->execute();
        $html .= "Supression de la série à vos préférences effectué <br>";
        $html .= "<a href='index.php'>Retour à l'accueil</a>";

        return $html;
    }
}
