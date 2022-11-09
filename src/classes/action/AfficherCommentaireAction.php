<?php

namespace sae\web\action;

use sae\web\factory\ConnectionFactory;

class AfficherCommentaireAction extends Action
{

    public function execute(): string
    {
        $html = "";
        $bd = ConnectionFactory::makeConnection();
        $query = $bd->prepare("SELECT email, contenu, note FROM Commentaire WHERE serie_id = {$_GET['id']}");
        $query->execute();
        $html .= "<ul>";
        while($data = $query->fetch()){
            $user = $data['email'];
            $note = $data['note'];
            $contenu = $data['contenu'];
            $html .= "<li>Commentaire de : " . $user . "<br>Note : " . $note . "/5" .
                "<br><p>$contenu</p>";
        }
        $html .= "</ul>";
        $html .= "<a href='index.php'>Retour à l'accueil</a>";
        return $html;
    }
}