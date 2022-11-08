<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AfficherDetailEpisodeAction extends Action
{

    public function execute(): string {

    $html = "";
    $bd =ConnectionFactory::makeConnection();
    $query = $bd->prepare("SELECT titre,resume,duree FROM episode WHERE id = ?");
    $query->execute([$_GET['id']]);

    $data = $query->fetch();
        $html.= "Titre : " . $data['titre'] .
        "<br> Resume : " . $data['resume'] .
        "<br> Duree: " . $data['duree'];

    return $html;
    }
}
