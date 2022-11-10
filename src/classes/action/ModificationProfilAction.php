<?php

namespace sae\web\action;

use sae\web\authentification\Authentification;
use sae\web\factory\ConnectionFactory;

class ModificationProfilAction extends Action
{
    public function execute(): string
    {
        $html = "";

        // connexion à la BDD
        $bd = ConnectionFactory::makeConnection();

        // vérification qu'un utilisateur est connecté
        if (isset($_SESSION['user'])) {
            if ($_SERVER['REQUEST_METHOD'] === "GET") {

                // requêtes pour récupérer les informations de l'utilisateur
                $queryNom = $bd->prepare("SELECT nom FROM Utilisateur WHERE email = '{$_SESSION['user']}'");
                $queryPrenom = $bd->prepare("SELECT prenom FROM Utilisateur WHERE email = '{$_SESSION['user']}'");
                $queryGenrePref = $bd->prepare("SELECT genrePref FROM Utilisateur WHERE email = '{$_SESSION['user']}'");

                $queryNom->execute();
                $queryPrenom->execute();
                $queryGenrePref->execute();

                if ($queryNom->rowCount() > 0) {
                    $dataNom = $queryNom->fetch();
                    $nom = $dataNom['nom'];
                } else {
                    $nom = "";
                }
                if ($queryPrenom->rowCount() > 0) {
                    $dataPrenom = $queryPrenom->fetch();
                    $prenom = $dataPrenom['prenom'];
                } else {
                    $prenom = "";
                }
                if ($queryGenrePref->rowCount() > 0) {
                    $dataGenrePref = $queryGenrePref->fetch();
                    $genrePref = $dataGenrePref['genrePref'];
                } else {
                    $genrePref = "";
                }

                $html .= "Vos informations actuelles : <br>";
                if ($nom != "") {
                    $html .= "Nom : " . $nom . "<br>";
                } else {
                    $html .= "Pas de nom renseigné !<br>";
                }
                if ($prenom != "") {
                    $html .= "Prénom : " . $prenom . "<br>";
                } else {
                    $html .= "Pas de prénom renseigné !<br>";
                }
                if ($genrePref != "") {
                    $html .= "Genre préféré : " . $genrePref . "<br>";
                } else {
                    $html .= "Pas de genre préféré renseigné !<br>";
                }

                //formulaire d'ajout des informations de l'utilisateur après vérification
                $html .= "<br>Mettez à jour vos données ! <br>";
                $html .= <<<HTML
                <form action=?action={$_GET['action']} method="post">
                    <label><b>Nom</b></label>
                        <input type="text" name="nom" placeholder="Votre nom">
                    <label><b>Prénom</b></label>
                        <input type="text" name="prenom" placeholder="Votre prénom">
                    <label><b>Genre préféré</b></label>
                        <select name="genre">
                        <option value="null"></option>
                        <option value="horreur">Horreur</option>
                        <option value="SF">Science Fiction</option>
                        <option value="romance">Romance</option>
                        <option value="biopic">Biopic</option>
                        <option value="action">Action</option>
                    </select>
                    <button type="submit">Mettre à jour</button>
                </form>
                HTML;

                $html .= "<a href='index.php'>Retour à l'accueil</a>";
            } else {
                $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
                $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
                $genrePref = filter_var($_POST['genre'], FILTER_SANITIZE_STRING);

                // requêtes pour mettre à jour les informations de l'utilisateur
                if ($nom != "") {
                    $queryUpdateNom = $bd->prepare("UPDATE Utilisateur SET nom = '$nom' WHERE email = '{$_SESSION['user']}'");
                    $queryUpdateNom->execute();
                }
                if ($prenom != "") {
                    $queryUpdatePrenom = $bd->prepare("UPDATE Utilisateur SET prenom = '$prenom' WHERE email = '{$_SESSION['user']}'");
                    $queryUpdatePrenom->execute();
                }
                if ($genrePref != "" && $genrePref != "null") {
                    $queryUpdateGenrePref = $bd->prepare("UPDATE Utilisateur SET genrePref = '$genrePref' WHERE email = '{$_SESSION['user']}'");
                    $queryUpdateGenrePref->execute();
                }

                $html .= "Vos informations ont bien été mises à jour !";
                $html .= "<br><a href='index.php'>Retour à l'accueil</a>";

            }
        } else {
            $html .= "Que faites-vous là ?.. 🔫";
        }
        return $html;
    }
}