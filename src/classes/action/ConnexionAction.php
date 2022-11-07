<?php

namespace sae\web\action;

use sae\web\exception\MotDePasseException;
use sae\web\authentification\Authentification;

class ConnexionAction extends Action
{
    public function execute(): string
    {
        $res = "";
        $authHTML = <<<HTML
            <form action="?action=${_GET['action']}" method="post">
                <label>Email: </label><input type="text" name="email" placeholder="toto@gmail.com" required>
                <label>Password: </label><input type="password" name="password" required>
                <button type="submit">Validate</button>
            </form>
        HTML;
        if ($_SERVER['REQUEST_METHOD'] === "GET") echo $authHTML;
        else {
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $passwd = filter_var($_POST['password']);
            try {
                Authentification::authenticate($email,$passwd);
            }catch (MotDePasseException $e){
                return "Mot de passe ou email erroné.</br><a href='index.php'>Retour a l'accueil</a>";
            }
            $res = "Connection réussite.</br><a href='index.php'>Retour a l'accueil</a>";
        }
        return $res;
    }
}