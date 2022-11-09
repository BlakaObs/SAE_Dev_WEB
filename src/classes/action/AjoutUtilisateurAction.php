<?php

namespace sae\web\action;

use sae\web\authentification\Authentification;
use sae\web\exception\EmailDejaExistantException;
use sae\web\exception\MotDePasseTropCourtException;

class AjoutUtilisateurAction extends Action
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
                            <h1>Inscription</h1>
                            <label><b>Email</b></label>
                                <input type="text" name="email" placeholder="mail@mail.com" required>
                            <label><b>Mot de passe</b></label>
                                <input type="password" placeholder="Entrer le mot de passe" name="password" required>
                            <label><b>Confirmation du mot de passe</b></label>
                                <input type="password" placeholder="Confirmer le mot de passe" name="passwordCheck" required>
                                <input type="submit" id='submit' value="S'inscire" >
                        </form>
                    </div>
                </body>
            </html>
            <link rel="stylesheet" href="connexion.css" type="text/css" />
            </form>
            HTML;
        } else {
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $passwd = filter_var($_POST['password']);
            $passwdCheck = filter_var($_POST['passwordCheck']);
            if ($passwd != $passwdCheck) {
                $html .= <<<HTML

                <html>                       
                    <h1>      
                        <p>Les mots de passes ne sont pas identiques</p>
                    </h1> 
                        <ok><a href='index.php'>Retour a l'accueil</a>   </ok>              
                </html>
                <link rel="stylesheet" href="connexion.css" type="text/css" />
                HTML;
            } else {
                try {
                    if (Authentification::register($email, $passwd)) {

                        $html .= <<<HTML

                <html>                       
                    <h1>      
                        <p>Compte correctement crée</p>
                    </h1> 
                        <ok><a href='index.php'>Retour a l'accueil</a>   </ok>              
                </html>
                <link rel="stylesheet" href="connexion.css" type="text/css" />
                HTML;
                    } else    $html .= <<<HTML

                <html>                       
                    <h1>      
                        <p>Veuillez rentrer un email correct</p>
                    </h1> 
                        <ok><a href='index.php'>Retour a l'accueil</a>   </ok>              
                </html>
                <link rel="stylesheet" href="connexion.css" type="text/css" />
                HTML;
                } catch (EmailDejaExistantException $e) {
                    $html .= <<<HTML

                <html>                       
                    <h1>      
                        <p>L'email est déjà enregistré</p>
                    </h1> 
                        <ok><a href='index.php'>Retour a l'accueil</a>   </ok>              
                </html>
                <link rel="stylesheet" href="connexion.css" type="text/css" />
                HTML;
                } catch (MotDePasseTropCourtException $e) {

                    $html .= <<<HTML

                <html>                       
                    <h1>      
                        <p>Votre mot de passe est trop court</p>
                    </h1> 
                        <ok><a href='index.php'>Retour a l'accueil</a>   </ok>              
                </html>
                <link rel="stylesheet" href="connexion.css" type="text/css" />
                HTML;
                }
            }
        }
        return $html;
    }
}