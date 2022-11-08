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
        <a href="?action=inscription">Inscription</a><br>
        <a href="?action=connexion">Connexion</a><br>
        HTML;

    if (isset($_SESSION['user'])) {
        $bd = ConnectionFactory::makeConnection();
        $query = $bd->prepare("SELECT titre FROM serie INNER JOIN seriePref ON serie.id=seriePref.serie_id WHERE email = ? AND pref = 1");
        $email = $_SESSION['user'];
        $query->bindParam(1, $email);
        $query->execute();

        $action = <<< HTML
        <p>Connecté en tant que : {$_SESSION['user']}</p> 
        <a href="?action=affichageListe">Afficher le catalogue de séries</a><br>
        HTML;
        if ($query->rowCount() == 0) {
            $action .= "<br>Aucune série préférée 😢<br>";
        } else {
            $action .= "<br>Mes séries préférées !<ul>";

            while ($data = $query->fetch()) {
                $action .= "<li> {$data['titre']}</li>";
            }

            $action .= <<<HTML
        </ul>
        HTML;
        }
        $action .= '<a href="?action=deconnexion">Se déconnecter</a>';
    }
    echo $action;
}