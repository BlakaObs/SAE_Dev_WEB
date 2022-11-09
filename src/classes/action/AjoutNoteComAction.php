<?php

namespace sae\web\action;

use sae\web\exception\MotDePasseException;
use sae\web\exception\MotDePasseTropCourtException;
use sae\web\authentification\Authentification;
use sae\web\factory\ConnectionFactory;

class AjoutNoteComAction extends Action
{
    public function execute(): string
    {
        $html = "";
        if ($_SERVER['REQUEST_METHOD'] === "GET"){
            $html .= <<<HTML
                <form action=?action={$_GET['action']}&id={$_GET['id']} method="post">
                    <textarea name="commentaire" rows="7" cols="70" placeholder="ajouter un commentaire"></textarea>
                    <select name="note">
                        <option value="0">0</option>
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
            $bd = ConnectionFactory::makeConnection();

            $queryAjoutCommentaire = $bd->prepare("INSERT INTO Commentaire VALUES ('{$_SESSION['user']}', '{$_GET['id']}', '$commentaire', '$note')");
            $queryAjoutCommentaire->execute();

            $html .= '<p>Commentaire publié ! Merci de votre retour !</p><br><a href="index.php">Retour à l\'accueil</a>';
        }
        return $html;
    }
}