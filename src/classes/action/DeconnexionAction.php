<?php

namespace sae\web\action;

class DeconnexionAction extends Action
{
    public function execute(): string
    {

        // destruction de la session (deconnexion)
        session_destroy();
        $html = "Vous avez été déconnecté";
        $html .= "<br><a href='index.php'>Retour</a>";
        return $html;
    }
}