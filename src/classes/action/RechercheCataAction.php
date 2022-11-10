<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class RechercheCataAction extends Action
{

    public function execute(): string
    {
        $html = <<<HTML
                <link rel='stylesheet' href='css/serie.css' type='text/css' />
                <html>
                    <body>
                        <div class="parent">
                           
                HTML;


        // connexion à la BDD
        // requête pour récupérer les informations de la série
        $bd = ConnectionFactory::makeConnection();
        $query = $bd->prepare("SELECT id, titre, img, descriptif  FROM serie");
        $query->execute();

        $recherche = explode(" ",$_POST['recherche']);
        while($data = $query->fetch()){
            for ($i = 0; $i < sizeof($recherche);$i++) {
                if (str_contains(strtolower($data['titre']), strtolower($recherche[$i])) || str_contains(strtolower($data['descriptif']), strtolower($recherche[$i]))) {
                    $id = $data['id'];
                    $html .= "<li><a href='?action=afficherDetailSerie&id=$id'>Titre : " . $data['titre'] . "</a><br><img src='src/ressources/images/" . $data['img']
                        . "' alt='Image correspondant à la série '></li><br>";
                }
            }
        }

        $html .= "<a href='index.php'>Retour à l'accueil</a>";
        return $html;
    }
}