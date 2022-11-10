<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AffichageListeAction extends Action
{

    public function execute(): string
    {
        $html = "";

        // vérification qu'un utilisateur est connecté
        if (isset($_SESSION['user'])) {

            // connexion à la BDD
            // requête pour récupérer les informations de la série
            $bd = ConnectionFactory::makeConnection();
            $query = $bd->prepare("SELECT id, titre, img FROM serie");
            $query->execute();

            $html .= <<<html
                <form action="?action=recherche&" method="post">
                    <input type='text' name='recherche' required> 
                    <button type='submit'>Rechercher</button>
                </form>
            html;

            $html .= "<ul>";

            // affichage des différentes séries
            while ($data = $query->fetch()) {
                $id = $data['id'];
                $html .= "<li><a href='?action=afficherDetailSerie&id=$id'>Titre : " . $data['titre'] . "</a><br><img src='src/ressources/images/" . $data['img']
                    . "' alt='Image correspondant à la série '></li><br>";
            }
            $html .= "</ul>";
            $html .= "<a href='index.php'>Retour à l'accueil</a>";
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