<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AfficherDetailSerieAction extends Action
{
    public function execute(): string
    {
        $html = "";

        // connexion à la BDD
        $bd = ConnectionFactory::makeConnection();

        // requête pour mettre à jour la moyenne de la série (ex: ajout d'un commentaire)
        $queryUpdateMoyenne = $bd->prepare("UPDATE serie SET moyenne = (SELECT TRUNCATE(AVG(note),2) FROM Commentaire WHERE serie_id = ?) WHERE id = ?");
        $queryUpdateMoyenne->bindParam(1, $_GET['id']);
        $queryUpdateMoyenne->bindParam(2, $_GET['id']);
        $queryUpdateMoyenne->execute();


        // vérification qu'un utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // requête pour sélectionner les informations de la série
            $querySerie = $bd->prepare("SELECT titre, descriptif, annee, date_ajout FROM serie WHERE id = ?");

            // requête pour sélectionner le nombre d'épisodes
            $queryNbEpisodes = $bd->prepare("SELECT COUNT(*) FROM episode WHERE serie_id = ?");

            // requête pour sélectionner les informations des épisodes
            $queryEpisode = $bd->prepare("SELECT id,numero, titre, duree FROM episode WHERE serie_id = ?");

            // requête pour sélectionner la moyenne de la série
            $queryMoyenne = $bd->prepare("SELECT moyenne FROM serie WHERE id = ? ");
            $queryMoyenne->execute([$_GET['id']]);
            $querySerie->execute([$_GET['id']]);
            $queryEpisode->execute([$_GET['id']]);
            $queryNbEpisodes->execute([$_GET['id']]);

            $moyenne = $queryMoyenne->fetch()[0];

            // affichage du détail des séries et des épisodes
            while ($data = $querySerie->fetch()) {
                $html .= <<<HTML
                <html>
                    <body>
                        <div class="parent">
                           
                HTML;

                $html .= "<link rel='stylesheet' href='css/serie.css' type='text/css' />";
                $html .= " <div class='div6'> <h1> Titre : " . $data['titre'] ."</h1></div>"   .
                    "<div class='div7'>  Description : " . $data['descriptif'] .
                    "</div><div class='div8'> Année : " . $data['annee'] .
                    "<br>Date d'ajout : " . $data['date_ajout'];
                if ($moyenne == 0.0) {
                    $html .= "<br> Nouvelle serie qui n'a pas encore de note <br> ";
                } else {
                    $html .= "<br> Note Moyenne : " . $moyenne . "<br>";
                }
                $html .= "Nombre d'épisodes : " . $queryNbEpisodes->fetch()[0] . "</div>" ;

                $queryEpisode->execute([$_GET['id']]);

                while ($data = $queryEpisode->fetch()) {
                    $id = $data['id'];
                    $numero = $data['numero'];

                    $html .= "<div class='div$numero' <li>Episode : " . $data['numero'] .
                        "<br> <a href='?action=afficherDetailEpisode&id=$id'>Titre : " . $data['titre'] . "<br></a>" .
                        " Durée : " . $data['duree'] . "</li></div>";
                }

            }

            // affichage du bouton des commentaires
            if ($moyenne != 0.0) {
                $html .= <<<form
            <div class='div9'>
                <form action="?action=afficherCommentaire&id=${_GET['id']}" method="post">
                   <button type='submit'style='width:100%'>Afficher les commentaires</button>
                </form>
            </div>
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
            
            <div class='div10'>
                <form action="?action=ajoutPreferences&id=${_GET['id']}" method="post">
                    <button type='submit' style='width:100%'>Ajouter à mes préférences</button>
                </form>
            </div>
            form;
            } else {
                $html .= <<<form
            <div class='div10'>
                <form action="?action=suprPreferences&id=${_GET['id']}" method="post">
                    <button type='submit' style='width:100%'>Retirer de mes préférences</button>
                </form>
            </div>
            form;
            }

            $html .= <<<HTML
              
                            <div class='div11'>
                                <a href='index.php'>Retour à l'accueil</a>
                            </div>
                        </div>
                    </body>
                </html> 
              HTML;
        } else {
            $html .= <<<HTML
                <html>
                    <body id="fondRock">                     
                        <h1>      
                            <p>Que faites-vous là ?.. 🔫</p>
                        </h1>
                        <link rel="stylesheet" href="css/rock.css" type="text/css" />     
                    </body>              
                </html>
                HTML;
        }
        return $html;
    }
}