<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AjoutPreferencesAction extends Action
{

    public function execute(): string
    {
        $html = "";
        $bd = ConnectionFactory::makeConnection();
        //$query = $bd->prepare("UPDATE  FROM Utilisateur");
        //$query->execute();
        $html .= "Ajout de la série à vos préférences éffectuer <br>";
        $html .= "<a href='index.php'>Retour à l'accueil</a>";

        return $html;
    }
}