<?php

namespace sae\web\authentification;

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
        return true;
    }

    public static function authenticate(string $email, string $mdp): bool
    {
        $bd = ConnectionFactory::makeConnection();
        $query = $bd->prepare("select * from Utilisateur where email = ? ");
        $query->bindParam(1, $email);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $mdpCourant = $data['password'];
        if (!password_verify($mdp, $mdpCourant)) return false;
        return true;
    }
}