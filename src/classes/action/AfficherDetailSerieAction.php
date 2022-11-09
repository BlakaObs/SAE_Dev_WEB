<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AfficherDetailSerieAction extends Action
{
    public function execute(): string
    {
        $html = "";

        // vérification qu'un utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // connexion à la BDD
            // requête pour sélectionner les informations de la série
            $bd = ConnectionFactory::makeConnection();
            $querySerie = $bd->prepare("SELECT titre, descriptif, annee, date_ajout FROM serie WHERE id = ?");

            // requête pour sélectionner le nombre d'épisodes
            $queryNbEpisodes = $bd->prepare("SELECT COUNT(*) FROM episode WHERE serie_id = ?");

            // requête pour sélectionner les informations des épisodes
            $queryEpisode = $bd->prepare("SELECT id,numero, titre, duree FROM episode WHERE serie_id = ?");

            // requête pour mettre à jour la moyenne de la série (ex: ajout d'un commentaire)
            $queryUpdateMoyenne = $bd->prepare("UPDATE serie SET moyenne = (SELECT TRUNCATE(AVG(note),2) FROM Commentaire WHERE serie_id = ?) WHERE id = ?");
            $queryUpdateMoyenne->bindParam(1, $_GET['id']);
            $queryUpdateMoyenne->bindParam(2, $_GET['id']);

            // requête pour sélectionner la moyenne de la série
            $queryMoyenne = $bd->prepare("SELECT moyenne FROM serie WHERE id = ? ");
            $queryMoyenne->execute([$_GET['id']]);
            $querySerie->execute([$_GET['id']]);
            $queryEpisode->execute([$_GET['id']]);
            $queryNbEpisodes->execute([$_GET['id']]);
            $queryUpdateMoyenne->execute();

            $moyenne = $queryMoyenne->fetch()[0];

            // affichage du détail des séries et des épisodes
            while ($data = $querySerie->fetch()) {
                $html .= "Titre : " . $data['titre'] .
                    "<br> Description : " . $data['descriptif'] .
                    "<br> Année : " . $data['annee'] .
                    "<br> Date d'ajout : " . $data['date_ajout'];
                if ($moyenne == 0.0) {
                    $html .= "<br> Nouvelle serie qui n'a pas encore de note <br> ";
                } else {
                    $html .= "<br> Note Moyenne : " . $moyenne . "<br>";
                }
                $html .= "Nombre d'épisodes : " . $queryNbEpisodes->fetch()[0] . "<br>";
                $html .= "Liste des épisodes : <br>";
                $queryEpisode->execute([$_GET['id']]);
                $html .= "<ul>";
                while ($data = $queryEpisode->fetch()) {
                    $id = $data['id'];
                    $html .= "<li>Numéro : " . $data['numero'] .
                        "<br> <a href='?action=afficherDetailEpisode&id=$id'>Titre : " . $data['titre'] . "</a>" .
                        "<br> Durée : " . $data['duree'] . "</li><br>";
                }
                $html .= "</ul>";
            }

            // affichage du bouton des commentaires
            if ($moyenne != 0.0) {
                $html .= <<<form
                <form action="?action=afficherCommentaire&id=${_GET['id']}" method="post">
                    <button type='submit'>Afficher les commentaires</button><br>
                </form>
            form;
            }

            // requête pour voir si la série est dans les préférences ou non
            $queryPref = $bd->prepare("SELECT pref FROM seriePref WHERE email = ? AND serie_id = ?");
            $email = $_SESSION['user'];
            $queryPref->bindParam(1, $email);
            $queryPref->bindParam(2, $_GET['id']);
            $queryPref->execute();

            $data = $queryPref->fetch();

            // affichage du bouton d'ajout/suppression des favoris
            if ($data['pref'] == 0) {
                $html .= <<<form
                <form action="?action=ajoutPreferences&id=${_GET['id']}" method="post">
                    <button type='submit'>Ajouter à mes préférences</button><br>
                </form>
            form;
            } else {
                $html .= <<<form
                <form action="?action=suprPreferences&id=${_GET['id']}" method="post">
                    <button type='submit'>Retirer de mes préférences</button><br>
                </form>
            form;
            }

            $html .= "<a href='index.php'>Retour à l'accueil</a>";
        } else {
            $html .= "Que faites-vous là ?.. 🔫";
        }
        return $html;
    }
}