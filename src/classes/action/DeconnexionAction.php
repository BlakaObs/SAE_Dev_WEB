<?php

namespace sae\web\action;

use sae\web\exception\MotDePasseException;
use sae\web\exception\MotDePasseTropCourtException;
use sae\web\authentification\Authentification;

class DeconnexionAction extends Action
{
    /**
     */
    public function execute(): string
    {
        session_destroy();
        $html = "Vous avez été déconnecté";
        $html .= "<br><a href='index.php'>Retour</a>";
        return $html;
    }
}