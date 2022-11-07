<?php

namespace sae\web\dispatch;

use sae\web\action\AffichageListeAction;
use sae\web\action\AjoutUtilisateurAction;
use sae\web\action\ConnexionAction;

class Dispatcher
{
    private string $action;

    public function __construct(string $q)
    {
        $this->action = $q;
    }

    public function run(): void
    {
        switch ($this->action) {
            case "Affichage_liste":
                $action = new AffichageListeAction();
                break;
            case "inscription":
                $action = new AjoutUtilisateurAction();
                break;
            case "connexion":
                $action = new ConnexionAction();
                break;
            default:
                $action = <<<HTML
                    <a href="?action=inscription">Inscription</a><br>
                    <a href="?action=connexion">ConnexionAction</a><br>                    
                    <a href="?action=Affichage_liste">AffichageListeAction</a><br>
                HTML;
                break;
        }
        $this->renderPage($action->execute());
    }

    public function renderPage(string $html): void
    {
        echo $html;
    }
}