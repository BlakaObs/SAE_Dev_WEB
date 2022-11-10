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

            $html .= <<<HTML
                <html>
                    <body>
                        <div class="parent">
                           
                HTML;

            $html .= "<link rel='stylesheet' href='css/episode.css' type='text/css' />";
            $html .= "<div class='div1'> <h1> Titre : " . $data['titre'] ."</h1></div>"   .
                "<br><div class='div5'> Resume : " . $data['resume'] .
                "<br> Duree: " . $data['duree'] . "</div><div class='div2'>" .
                '<br><video width="1280" height="720" controls >
                <source src="src/ressources/videos/' . $data['file'] . '" type="video/mp4" > </video><br></div>' .
                "<br><div class='div4'><a href='?action=ajoutNoteCom&id={$id}'>Écrire un commentaire</a></div>";

            $html .= <<<HTML
              
                            <div class='div3'>
                                <a href='index.php'>Retour à l'accueil</a>
                            </div>
                        </div>
                    </body>
                </html> 
              HTML;



            // requête permettant de mettre à jour la liste des séries en cours de l'utilisateur
            $update = $bd->prepare("UPDATE seriePref SET enCours = 1 WHERE serie_id = ? AND email = ?");
            $update->bindParam(1, $data['serie_id']);
            $update->bindParam(2, $_SESSION['user']);
            $update->execute();
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