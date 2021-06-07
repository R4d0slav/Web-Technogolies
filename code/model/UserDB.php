<?php

require_once "DBInit.php";

class UserDB {
    public static function validLoginAttempt($username) {
        $dbh = DBInit::getInstance();

        $stmt = $dbh->prepare("SELECT password FROM user WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        return $stmt->fetch();
    }

    public static function usernameExists($username) {
        $dbh = DBInit::getInstance();

        $stmt = $dbh->prepare("SELECT COUNT(id) FROM user WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        return $stmt->fetchColumn(0) == 1;
    }

    public static function emailExists($email) {
        $dbh = DBInit::getInstance();

        $stmt = $dbh->prepare("SELECT COUNT(id) FROM user WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetchColumn(0) == 1;
    }

    public static function addNewUser($firstname, $lastname, $username, $email, $password) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("INSERT INTO user (firstname, lastname, username, email, password) VALUES (:firstname, :lastname, :username, :email, :password)");
        $statement->bindParam(":firstname", $firstname);
        $statement->bindParam(":lastname", $lastname);
        $statement->bindParam(":username", $username);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":password", $password);
        $statement->execute();
    }

    public static function getIdFromUsername($username) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT id FROM user WHERE username = :username");
        $statement->bindParam(":username", $username);
        $statement->execute();

        return $statement->fetchColumn(0);
    }

    public static function getIdByUsername($username) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT id FROM user WHERE username = :username");
        $statement->bindParam(":username", $username);
        $statement->execute();

        return $statement->fetchColumn(0);
    }

}