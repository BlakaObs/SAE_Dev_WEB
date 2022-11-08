<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AffichageListeAction extends Action
{

    public function execute(): string
    {
        $html = "";
        $bd = ConnectionFactory::makeConnection();
        $query = $bd->prepare("SELECT titre,img FROM serie");
        $query->execute();
        $html .= "<ul>";
        while($data = $query->fetch()){
            $html .= "<li> Titre : " . $data['titre'] . "<br><img src='img/" . $data['img']
                . "' alt='Image correspondant à la série '></li><br>";
        }
        $html .= "</ul>";
        return $html;
    }
}