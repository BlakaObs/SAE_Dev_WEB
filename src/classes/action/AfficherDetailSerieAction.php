<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AfficherDetailSerieAction extends Action
{
    public function execute(): string
    {
        $html = "";
        $bd = ConnectionFactory::makeConnection();
        $querySerie = $bd->prepare("SELECT titre, descriptif, annee, date_ajout FROM serie WHERE id = ?");
        $queryNbEpisodes = $bd->prepare("SELECT COUNT(*) FROM episode WHERE serie_id = ?");
        $queryEpisode = $bd->prepare("SELECT id,numero, titre, duree FROM episode WHERE serie_id = ?");
        $querySerie->execute([$_GET['id']]);
        $queryEpisode->execute([$_GET['id']]);
        $queryNbEpisodes->execute([$_GET['id']]);
        while ($data = $querySerie->fetch()) {
            $html .= "Titre : " . $data['titre'] .
                "<br> Description : " . $data['descriptif'] .
                "<br> Année : " . $data['annee'] .
                "<br> Date d'ajout : " .
                $data['date_ajout'] . "<br>";
            $html .= "Nombre d'épisodes : " . $queryNbEpisodes->fetch()[0] . "<br>";
            $html .= "Liste des épisodes : <br>";
            $queryEpisode->execute([$_GET['id']]);
            $html .= "<ul>";
            while ($data = $queryEpisode->fetch()) {
                $id=$data['id'];
                $html .= "<li>Numéro : " . $data['numero'] .
                    "<br> <a href='?action=afficherDetailEpisode&id=$id'>Titre : " . $data['titre'] . "</a>" .
                    "<br> Durée : " . $data['duree'] . "</li><br>";
            }
            $html .= "</ul>";
        }

        $html .= <<<form
            <form action="?action=ajoutPreferences&id=${_GET['id']}" method="post">
                <button type='submit'>Ajouter à mes préférences</button><br>
            </form>
        form;

        $html .= "<a href='index.php'>Retour à l'accueil</a>";

        return $html;
    }
}