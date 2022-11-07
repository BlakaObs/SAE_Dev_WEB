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
            <form action="?action=${_GET['action']}" method="post">
                <label>Email: </label><input type="text" name="email" placeholder="mail@mail.com" required><br>
                <label>Mot de passe: </label><input type="password" name="password" required><br>
                 <label>Confirmer votre mot de passe: </label><input type="password" name="passwordCheck" required>     <br>       
                <button type="submit">Confirmer</button>
            </form>
            HTML;
        } else {
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $passwd = filter_var($_POST['password']);
            $passwdCheck = filter_var($_POST['passwordCheck']);
            if ($passwd != $passwdCheck) {
                $html .= "<p>Les mots de passe ne sont pas identiques</p>";
                $html .= <<<HTML
                    <a href="index.php">Retour à l'accueil</a>
                HTML;
            } else {
                try {
                    if (Authentification::register($email, $passwd)) {
                        $html .= "<p>Compte correctement crée</p>";
                    } else $html .= "<p>L'email existe déjà</p>";
                    $html .= "<br><a href='index.php'>Retour à l'accueil</a>";
                } catch (EmailDejaExistantException $e) {
                    $html .= "<p>L'email est déjà enregistré</p>";
                    echo "<br><a href='index.php'>Retour</a>";
                } catch (MotDePasseTropCourtException $e) {
                    $html .= "<p>Votre mot de passe est trop court</p>";
                    $html .= "<br><a href='index.php'>Retour à l'accueil</a>";
                }
            }
        }
        return $html;
    }
}