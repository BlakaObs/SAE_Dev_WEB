<?php

namespace sae\web\dispatch;

use sae\web\action\AffichageListeAction;
use sae\web\action\AfficherDetailEpisodeAction;
use sae\web\action\AfficherDetailSerieAction;
use sae\web\action\AjoutPreferencesAction;
use sae\web\action\AjoutUtilisateurAction;
use sae\web\action\ConnexionAction;
use sae\web\action\SupprPreferencesAction;

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
            case "affichageListe":
                $action = new AffichageListeAction();
                break;
            case "inscription":
                $action = new AjoutUtilisateurAction();
                break;
            case "connexion":
                $action = new ConnexionAction();
                break;
            case "afficherDetailSerie":
                $action = new AfficherDetailSerieAction();
                break;
            case "afficherDetailEpisode":
                $action = new AfficherDetailEpisodeAction();
                break;
            case "ajoutPreferences":
                $action = new AjoutPreferencesAction();
                break;
            case "suprPreferences":
                $action = new SupprPreferencesAction();
                break;
            default:
                $action = <<<HTML
                    <a href="?action=inscription">Inscription</a><br>
                    <a href="?action=connexion">ConnexionAction</a><br>                    
                    <a href="?action=affichageListe">AffichageListeAction</a><br>
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