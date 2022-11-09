<?php

namespace sae\web\authentification;

use sae\web\exception\ValiderException;
use http\Exception;
use PDO;
use sae\web\exception\EmailDejaExistantException;
use sae\web\exception\MotDePasseException;
use sae\web\exception\MotDePasseTropCourtException;
use sae\web\factory\ConnectionFactory;

class Authentification
{
    /**
     * @throws MotDePasseTropCourtException
     * @throws EmailDejaExistantException
     */
    public static function register(string $email, string $passwd2check): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
        $hash = password_hash($passwd2check, PASSWORD_DEFAULT, ['cost' => 12]);
        $bd = ConnectionFactory::makeConnection();
        $query = $bd->prepare("select * from Utilisateur where email = ?");
        $query->bindParam(1, $email);
        $query->execute();
        if ($query->rowCount() > 0) {
            throw new EmailDejaExistantException("Email déjà utilisé");
        }
        if (strlen($passwd2check) < 10) {
            throw new MotDePasseTropCourtException("Mot de passe trop court, min. 10 caractères");
        }
        $query = $bd->prepare("insert into Utilisateur (email, password) values(?, ?)");
        $query->bindParam(1, $email);
        $query->bindParam(2, $hash);
        $query->execute();
        $query2 = $bd->prepare("INSERT INTO seriePref (email, serie_id) VALUES(?, ?)");
        $query3 = $bd->prepare("SELECT id FROM serie");
        $query3->execute();
        $query2->bindParam(1, $email);
        while ($data = $query3->fetch()){
            $query2->bindParam(2, $data['id']);
            $query2->execute();
        }
        return true;
    }

    /**
     * @throws MotDePasseException
     * @throws \Exception
     */
    public static function authenticate(string $email, string $mdp): bool
    {
        $bd = ConnectionFactory::makeConnection();
        $query = $bd->prepare("select * from Utilisateur where email = ? ");
        $query->bindParam(1, $email);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        if ($query->rowCount() == 0) {
            throw new \Exception();
        } else {
            $mdpCourant = $data['password'];
        }
        if (!password_verify($mdp, $mdpCourant)) throw new MotDePasseException();
        if ($data['valid'] == 0) throw new ValiderException("Compte non valide");
        return true;
    }

    public static function validation(string $email):bool
    {
        $bd = ConnectionFactory::makeConnection();
        $query = $bd->prepare("UPDATE Utilisateur SET valid = 1 where email = ? ");
        $query->bindParam(1, $email);
        $query->execute();
        return true;
    }

    public static function suppression(string $email):void
    {
        $bd = ConnectionFactory::makeConnection();
        $query = $bd->prepare("DELETE FROM Utilisateur WHERE email = ? ");
        $query->bindParam(1, $email);
        $query->execute();
    }
}