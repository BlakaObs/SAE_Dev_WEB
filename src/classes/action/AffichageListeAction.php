<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AffichageListeAction extends Action
{

    public function execute(): string
    {
        $html = "";
        if (isset($_SESSION['user'])) {
            $bd = ConnectionFactory::makeConnection();
            $query = $bd->prepare("SELECT id, titre, img FROM serie");
            $query->execute();
            $html .= "<ul>";
            while ($data = $query->fetch()) {
                $id = $data['id'];
                $html .= "<li><a href='?action=afficherDetailSerie&id=$id'>Titre : " . $data['titre'] . "</a><br><img src='src/ressources/images/" . $data['img']
                    . "' alt='Image correspondant à la série '></li><br>";
            }
            $html .= "</ul>";
            $html .= "<a href='index.php'>Retour à l'accueil</a>";
        } else {
            $html .= "Que faites-vous là ?.. 🔫";
        }
        return $html;
    }
}