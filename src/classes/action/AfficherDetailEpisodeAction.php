<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AfficherDetailEpisodeAction extends Action
{

    public function execute(): string {

    $html = "";
    $bd =ConnectionFactory::makeConnection();
    $query = $bd->prepare("SELECT titre,resume,duree,file FROM episode WHERE id = ?");
    $query->execute([$_GET['id']]);

    $data = $query->fetch();
        $html.= "Titre : " . $data['titre'] .
        "<br> Resume : " . $data['resume'] .
        "<br> Duree: " . $data['duree'] .
            '<br><video width="1280" height="720" controls >
                <source src="src/ressources/videos/' .$data['file'] . '" type="video/mp4" > </video>';

    return $html;
    }
}
