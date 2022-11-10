<?php
require_once("vendor/autoload.php");

use sae\web\dispatch\Dispatcher;
use sae\web\factory\ConnectionFactory;
use sae\web\loader\Psr4ClassLoader;

session_start();
ConnectionFactory::setConfig("db.conf.ini");
if (isset($_GET['action'])) {
    $dispatcher = new Dispatcher($_GET['action']);
    $dispatcher->run();
} else {
    $action = <<< HTML
        <html>
            <link rel="stylesheet" href="css/connexion.css" type="text/css" />  
                <div class="parent">
                    <div class="div1">  
                        <h1>Bienvenue sur NetVOD</h1>
                       </div>
               
          
                <body>     
                    <div class="div2">
                        <h2>               
                            <a href="?action=connexion">Connexion</a>
                        </h2> 
                    </div>
                    <div class="div3"> 
                        <h2> 
                            <a  href="?action=inscription">Inscription  </a>
                        </h2>
                    </div>            
                </body>  
               </div> 
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
        <link rel="stylesheet" href="css/accueil.css" type="text/css" />
        <html>
        
            <h1>
                <p>Connecté en tant que : {$_SESSION['user']} 
                
                <a href="?action=modificationProfil">(modifier mon profil)</a></p> 
            </h1>           
            <div class="parent">     
                        <div class="div1">
                            <h3>
                                <a href="?action=affichageListe">Afficher le catalogue de séries</a>
                            </h3>
                        </div>
                
        </html>
        HTML;
        if ($queryPref->rowCount() == 0) {
            $action .= "<br><div class='div2'> Aucune série préférée 😢<br></div>";
        } else {
            $action .= <<< HTML
               <br><div class='div2'>  Mes séries préférées !
            HTML;

            while ($data = $queryPref->fetch()) {
                $action .= "    <li><a href=\"?action=afficherDetailSerie&id={$data['id']}\">{$data['titre']}</a></li>";

            }
            $action .= <<<HTML
        </div>
        HTML;
        }

        if ($queryEnCours->rowCount() > 0) {
            $action .= <<<HTML
            <div class='div3'>
              Mes séries en cours !
               
        
        HTML;

            $queryEnCours->execute();
            while ($data = $queryEnCours->fetch()) {

                $action .= "<li><a href=\"?action=afficherDetailSerie&id={$data['id']}\">{$data['titre']}</a></li>";
            }
            $action .= <<<HTML
        </a></div>
        HTML;
        }
        $action .= '<footer> <h2><deconnexion><a href="?action=deconnexion">Se déconnecter</a></deconnexion></h2></footer></div> ';

    }
    echo $action;
}