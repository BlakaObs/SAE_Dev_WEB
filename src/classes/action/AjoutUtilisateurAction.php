<?php

namespace sae\web\action;

use sae\web\authentification\Authentification;
use sae\web\exception\EmailDejaExistantException;
use sae\web\exception\MotDePasseTropCourtException;
use sae\web\factory\ConnectionFactory;

class AjoutUtilisateurAction extends Action
{
    public function execute(): string
    {
        Authentification::suppression();
        $html = "";
        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            // formulaire de création de compte
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
            <link rel="stylesheet" href="css/connexion.css" type="text/css" />
            </form>
            HTML;
        } else {
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $passwd = filter_var($_POST['password']);
            $passwdCheck = filter_var($_POST['passwordCheck']);

            // vérification des MDP
            if ($passwd != $passwdCheck) {
                $html .= <<<HTML
                    <html>                       
                        <h1>      
                            <p>Les mots de passe ne sont pas identiques</p>
                        </h1> 
                            <div class="div4">
                                <div>
                                    <h3>
                                        <a href='index.php'>Retour a l'accueil</a>
                                    </h3>
                                </div>             
                    </html>
                    <link rel="stylesheet" href="css/connexion.css" type="text/css" />
                    HTML;
            } else {
                try {
                    if (Authentification::register($email, $passwd)) {
                        $html .= <<<HTML
                    <html>                       
                        <h1>      
                            <p>Veuillez valider votre compte</p>
                        </h1> 
                            <div class="div4">
                                <div>
                                    <h3>
                                   
                    HTML;
                        $token = uniqid();
                        $_SESSION['token'] = $token;
                        $expire = time() + 60;
                        $html .= <<<HTML
                            <br><a href="?action=activation&token=$token&email=$email&expire=$expire" . ">Activation</a><br><br>

                         <a href='index.php'>Retour a l'accueil</a>
                                    </h3>
                                </div>             
                    </html>
                    <link rel="stylesheet" href="css/connexion.css" type="text/css" />
                    HTML;
                    } else   $html .= <<<HTML
                    <html>                       
                        <h1>      
                            <p>Veuillez rentrer un email correct</p>
                        </h1> 
                            <div class="div4">
                                <div>
                                    <h3>
                                        <a href='index.php'>Retour a l'accueil</a>
                                    </h3>
                                </div>             
                    </html>
                    <link rel="stylesheet" href="css/connexion.css" type="text/css" />
                    HTML;
                } catch (EmailDejaExistantException $e) {
                    $html .= <<<HTML
                    <html>                       
                        <h1>      
                            <p>L'email est déjà enregistré</p>
                        </h1> 
                            <div class="div4">
                                <div>
                                    <h3>
                                        <a href='index.php'>Retour a l'accueil</a>
                                    </h3>
                                </div>             
                    </html>
                    <link rel="stylesheet" href="css/connexion.css" type="text/css" />
                    HTML;
                } catch (MotDePasseTropCourtException $e) {
                    $html .= <<<HTML
                    <html>                       
                        <h1>      
                            <p>Votre mot de passe est trop court</p>
                        </h1> 
                            <div class="div4">
                                <div>
                                    <h3>
                                        <a href='index.php'>Retour a l'accueil</a>
                                    </h3>
                                </div>             
                    </html>
                    <link rel="stylesheet" href="css/connexion.css" type="text/css" />
                    HTML;
                }
            }
        }
        return $html;
    }
}