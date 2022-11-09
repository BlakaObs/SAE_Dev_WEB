<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AfficherDetailEpisodeAction extends Action
{

    public function execute(): string
    {
        $html = "";

        //vérification qu'un utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // connexion à la BDD
            // requête pour sélectionner les informations d'un épisode
            $bd = ConnectionFactory::makeConnection();
            $query = $bd->prepare("SELECT titre,resume,duree,file, serie_id FROM episode WHERE id = ?");
            $query->execute([$_GET['id']]);
            $data = $query->fetch();
            $id = $data['serie_id'];

            // affichage des informations
            $html .= "Titre : " . $data['titre'] .
                "<br> Resume : " . $data['resume'] .
                "<br> Duree: " . $data['duree'] .
                '<br><video width="1280" height="720" controls >
                <source src="src/ressources/videos/' . $data['file'] . '" type="video/mp4" > </video><br>' .
                "<br><a href='?action=ajoutNoteCom&id={$id}'>Écrire un commentaire</a>" .
                "<br><br><a href=\"index.php\">Retour à l'accueil</a>";

            // requête permettant de mettre à jour la liste des séries en cours de l'utilisateur
            $update = $bd->prepare("UPDATE seriePref SET enCours = 1 WHERE serie_id = ? AND email = ?");
            $update->bindParam(1, $data['serie_id']);
            $update->bindParam(2, $_SESSION['user']);
            $update->execute();
        } else {
            $html .= "Que faites-vous là ?.. 🔫";
        }
        return $html;
    }
}
