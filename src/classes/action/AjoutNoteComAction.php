<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AjoutNoteComAction extends Action
{
    public function execute(): string
    {
        $html = "";

        // vérification qu'un utilisateur est connecté
        if (isset($_SESSION['user'])) {
            if ($_SERVER['REQUEST_METHOD'] === "GET") {

                //formulaire d'ajout d'un commentaire et d'une note
                $html .= <<<HTML
                <form action=?action={$_GET['action']}&id={$_GET['id']} method="post">
                    <textarea name="commentaire" rows="7" cols="70" placeholder="ajouter un commentaire (490 caractères)"></textarea>
                    <select name="note">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <button type="submit">Poster</button>
                </form>
            HTML;
            } else {
                $commentaire = filter_var($_POST['commentaire'], FILTER_SANITIZE_STRING);
                $note = filter_var($_POST['note'], FILTER_SANITIZE_NUMBER_INT);

                // connexion à la BDD
                $bd = ConnectionFactory::makeConnection();

                // requête pour vérifier l'existence d'un commentaire
                $queryCheckCommentaire = $bd->prepare("SELECT * FROM Commentaire WHERE email = '{$_SESSION['user']}' and serie_id = '{$_GET['id']}'");
                $queryCheckCommentaire->execute();

                if ($queryCheckCommentaire->rowCount() > 0) {
                    $html .= <<<HTML
                    <html>                       
                        <h1>      
                            <p>Vous avez déjà écris un commentaire pour cette série</p>
                        </h1> 
                            <div class="div4">
                                <div>
                                    <h3>
                                        <a href='index.php'>Retour a l'accueil</a>
                                    </h3>
                                </div>             
                    </html>
                    <link rel="stylesheet" href="css/connexion.css" type="text/css" />
                    HTML;

                } else {

                    // requête pour insérer un commentaire dans la BDD
                    $queryAjoutCommentaire = $bd->prepare("INSERT INTO Commentaire VALUES ('{$_SESSION['user']}', '{$_GET['id']}', '$commentaire', '$note')");
                    $queryAjoutCommentaire->execute();

                    $html .= <<<HTML
                    <html>                       
                        <h1>      
                            <p>Commentaire publié</p>
                        </h1> 
                            <div class="div4">
                                <div>
                                    <h3>
                                        <a href='index.php'>Retour a l'accueil</a>
                                    </h3>
                                </div>             
                    </html>
                    <link rel="stylesheet" href="css/connexion.css" type="text/css" />
                    HTML;
                }
            }
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