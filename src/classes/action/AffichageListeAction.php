<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AffichageListeAction extends Action
{

    public function execute(): string
    {
        $html = "";
        $bd = ConnectionFactory::makeConnection();
        $query = $bd->prepare("select titre,img from serie");
        $query->execute();
        $html .= "<ul>";
        foreach ($query->fetch() as $res){
            $html .= "<li> Titre : " . $res['titre'] . "<img src='img/" . $res['img'] . "' alt='Image correspondant à : " . $res['titre'] . "'></li>";
        }
        $html .= "</ul>";
        return $html;
    }
}