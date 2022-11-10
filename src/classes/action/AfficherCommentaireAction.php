<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AfficherCommentaireAction extends Action
{
    public function execute(): string
    {
        $html = "";

        // vérification qu'un utilisateur est connecté
        if (isset($_SESSION['user'])) {

            //connexion à la BDD
            // requête pour sélectionner les commentaires d'une série
            $bd = ConnectionFactory::makeConnection();
            $query = $bd->prepare("SELECT email, contenu, note FROM Commentaire WHERE serie_id = {$_GET['id']}");
            $query->execute();
            $html .= "<ul>";

            // affichage des commentaires
            while ($data = $query->fetch()) {
                $user = $data['email'];
                $note = $data['note'];
                $contenu = $data['contenu'];
                $html .= "<li>Commentaire de : " . $user . "<br>Note : " . $note . "/5" .
                    "<br><p>$contenu</p>";
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