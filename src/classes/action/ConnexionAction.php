<?php

namespace sae\web\action;

use Exception;
use sae\web\exception\MotDePasseException;
use sae\web\authentification\Authentification;
use sae\web\exception\ValiderException;

class ConnexionAction extends Action
{
    public function execute(): string
    {
        $html = "";
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            $html .= <<<HTML
        <html>
            <body>
                <div id="container">
                    <form action="?action=${_GET['action']}" method="post">
                        <h1>Connexion</h1>
                            <label><b>Email</b></label>
                                <input type="text" name="email" placeholder="mail@mail.com" required>
                            <label><b>Mot de passe</b></label>
                                <input type="password" placeholder="Entrer le mot de passe" name="password" required>
                                <input type="submit" id='submit' value='Se connecter' >
                    </form>
                </div>
            </body>
        </html>
        <link rel="stylesheet" href="connexion.css" type="text/css" />

        HTML;
        } else {
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $passwd = filter_var($_POST['password']);
            try {
                Authentification::authenticate($email, $passwd);
                $html .= <<<HTML

                <html>                       
                    <h1>      
                        <p>Connexion réussie</p>
                    </h1> 
                        <ok><a href='index.php'>Retour a l'accueil</a>   </ok>              
                </html>
                <link rel="stylesheet" href="connexion.css" type="text/css" />
                HTML;
                $_SESSION['user']=$email;
            }catch (MotDePasseException $e){
                $html .= <<<HTML

                <html>                       
                    <h1>      
                        <p>Le mot de passe est incorrect</p>
                    </h1> 
                        <ok><a href='index.php'>Retour a l'accueil</a>   </ok>              
                </html>
                <link rel="stylesheet" href="connexion.css" type="text/css" />
                HTML;

            }catch (ValiderException $e){
                $html .= "<p>Le compte n'est pas valide.</p>";
                $html .= "<br><a href='index.php'>Retour</a>";
            }catch (Exception $e){
                $html .= <<<HTML

                <html>                       
                    <h1>      
                        <p>Le compte n'existe pas</p>
                    </h1> 
                        <ok><a href='index.php'>Retour a l'accueil</a>   </ok>              
                </html>
                <link rel="stylesheet" href="connexion.css" type="text/css" />
                HTML;
            }
        }
        return $html;
    }
}