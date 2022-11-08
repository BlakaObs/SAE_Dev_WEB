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

    if(isset($_SESSION['user'])){
    $action = <<< HTML
        <a href="?action=inscription">Inscription</a><br>
        <a href="?action=connexion">Connexion</a><br>
        <a href="?action=affichageListe">Afficher le catalogue de séries</a><br>
        HTML;
}
    echo $action;
}