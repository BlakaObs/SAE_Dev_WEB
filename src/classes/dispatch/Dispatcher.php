<?php

namespace sae\web\dispatch;

use sae\web\action\AjoutUtilisateur;
use sae\web\action\Connexion;

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
            case "inscription":
                $action = new AjoutUtilisateur();
                break;
            case "connexion":
                $action = new Connexion();
                break;
            default:
                $action = <<<HTML
                    <a href="?action=inscription">Inscription</a><br>
                    <a href="?action=connexion">Connexion</a><br>
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