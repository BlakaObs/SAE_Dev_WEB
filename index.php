<?php
require_once 'src/classes/loader/Psr4ClassLoader.php';

use sae\web\dispatch\Dispatcher;
use sae\web\factory\ConnectionFactory;
use sae\web\loader\Psr4ClassLoader;

session_start();
$loader = new Psr4ClassLoader('sae\\web\\', 'src/classes/');
$loader->register();
ConnectionFactory::setConfig("db.conf.ini");
if (isset($_GET['action'])) {
    $dispatcher = new Dispatcher($_GET['action']);
    $dispatcher->run();
} else {
    $action = <<< HTML
        <html>
        <plan>
            <header>
                <h1>Bienvenue sur NetVOD</h1>
            </header>
              <body>           
                        <a style="display: inline" href="?action=connexion">Connexion</a> <a style="display: inline" <a href="?action=inscription">Inscription  </a>            
              </body>
        <link rel="stylesheet" href="connexion.css" type="text/css" />
        </plan>
        </html>
        HTML;

    if (isset($_SESSION['user'])) {
        $bd = ConnectionFactory::makeConnection();
        $queryPref = $bd->prepare("SELECT titre, id FROM serie INNER JOIN seriePref ON serie.id=seriePref.serie_id WHERE email = ? AND pref = 1");
        $queryEnCours = $bd->prepare("SELECT titre, id FROM serie INNER JOIN seriePref ON serie.id=seriePref.serie_id WHERE email = ? AND enCours = 1");
        $email = $_SESSION['user'];
        $queryPref->bindParam(1, $email);
        $queryPref->execute();
        $queryEnCours->bindParam(1, $email);
        $queryEnCours->execute();

        $action = <<< HTML
        
        <html>
            <p>Connecté en tant que : {$_SESSION['user']}</p> 
                <a href="?action=affichageListe">Afficher le catalogue de séries</a><br>
            <link rel="stylesheet" href="accueil.css" type="text/css" />
        </html>
        HTML;
        if ($queryPref->rowCount() == 0) {
            $action .= "<br>Aucune série préférée 😢<br>";
        } else {
            $action .= "<br>Mes séries préférées !<ul>";

            while ($data = $queryPref->fetch()) {
                $action .= "<li><a href=\"?action=afficherDetailSerie&id={$data['id']}\">{$data['titre']}</a></li>";
            }

            $action .= <<<HTML
        </ul>
        HTML;
        }

        if ($queryEnCours->rowCount() > 0) {
            $action .= <<<HTML
        <a>Mes séries en cours !
        <ul>
        HTML;

            $queryEnCours->execute();
            while ($data = $queryEnCours->fetch()) {

                $action .= "<li><a href=\"?action=afficherDetailSerie&id={$data['id']}\">{$data['titre']}</a></li>";
            }
            $action .= <<<HTML
        </ul></a>
        HTML;
        }
        $action .= '<a href="?action=deconnexion">Se déconnecter</a>';

    }
    echo $action;
}