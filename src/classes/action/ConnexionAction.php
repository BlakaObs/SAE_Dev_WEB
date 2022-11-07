<?php

namespace sae\web\action;

use sae\web\exception\MotDePasseException;
use sae\web\exception\MotDePasseTropCourtException;
use sae\web\authentification\Authentification;

class ConnexionAction extends Action
{
    /**
     */
    public function execute(): string
    {
        $html = "";
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            $html .= <<<HTML
            <form action="?action=${_GET['action']}" method="post">
                <label>Email: </label><input type="text" name="email" placeholder="mail@mail.com" required>
                <label>Mot de passe: </label><input type="password" name="password" required>
                <button type="submit">Valider</button>
            </form>
        HTML;
        } else {
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $passwd = filter_var($_POST['password']);
            try {
                Authentification::authenticate($email, $passwd);
                $html .= "<p>Connexion réussie</p>";
                $html .= "<a href='index.php'>Retour a l'accueil</a>";
            }catch (MotDePasseException $e){
                $html .= "<p>Le mot de passe est incorrect.</p>";
                echo "<br><a href='index.php'>Retour</a>";
            }
        }
        return $html;
    }
}