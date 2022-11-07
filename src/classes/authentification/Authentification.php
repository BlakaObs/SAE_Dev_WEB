<?php

namespace sae\web\authentification;

use sae\web\exception\MotDePasseException;

class Authentification
{
    /**
     * @throws MotDePasseException
     */
    public static function authenticate(string $email, string $passwd2check): void {
        $bd = ConnectionFactory::makeConnection();
        $query = $bd->prepare("select * from Utilisateur where email = ? ");
        $query->bindParam(1, $email);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $hash = $data['passwd'];
        if (!password_verify($passwd2check, $hash)) {
            throw new MotDePasseException();
        }
    }
}