<?php

require_once "DBInit.php";

Class TvShowDB {

    public static function init($id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("INSERT INTO tvShows (title, description, year, img_url, user_id, favorite) SELECT title, description, year, img_url, :id as user_id, favorite from tvShows WHERE user_id IS NULL");
        $statement->bindParam(":id", $id);
        $statement->execute();

    }

    public static function getIds($id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT id FROM tvShows WHERE user_id = :id");
        $statement->bindParam(":id", $id);
        $statement->execute();

        return $statement->fetchAll();

    }

    public static function getForId($id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT * FROM tvShows WHERE id = :id");
        $statement->bindParam(":id", $id);
        $statement->execute();

        return $statement->fetch();
    }
    public static function getAllForUser($user_id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT * FROM tvShows WHERE user_id = :user_id");
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();
        
        return $statement->fetchAll();
    }

    public static function getAllDefault() {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT * FROM tvShows WHERE user_id IS NULL");
        $statement->execute();
        
        return $statement->fetchAll();
    }

    public static function addTvShow($user_id, $title, $img_url) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("INSERT INTO tvShows (title, img_url, user_id) VALUES (:title, :img_url, :user_id)");
        $statement->bindParam(":title", $title);
        $statement->bindParam(":img_url", $img_url);
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();
    }

    public static function searchDefault($query) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT * FROM tvShows WHERE title LIKE :query AND user_id IS NULL");
        $statement->bindValue(":query", "%" . $query . "%");
        $statement->execute();

        return $statement->fetchAll();
    }

    public static function searchById($id, $query) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT * FROM tvShows WHERE title LIKE :query AND user_id = :id");
        $statement->bindValue(":query", "%" . $query . "%");
        $statement->bindParam(":id", $id);
        $statement->execute();

        return $statement->fetchAll();
    }

    public static function updateDescription($id, $data) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE tvShows SET description = :data WHERE id = :id");
        $statement->bindParam(":data", $data);
        $statement->bindParam(":id", $id);
        $statement->execute();
    }

    public static function updateYear($id, $data) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE tvShows SET year = :year WHERE id = :id");
        $statement->bindParam(":id", $id);
        $statement->bindParam(":year", $data);
        $statement->execute();
    }

    public static function updateTitle($id, $data) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE tvShows SET title = :data WHERE id = :id");
        $statement->bindParam(":data", $data);
        $statement->bindParam(":id", $id);
        $statement->execute();
    }

    public static function getAllFavorites($id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT * FROM tvShows WHERE favorite = true AND (user_id = :id OR user_id IS NULL)");
        $statement->bindParam(":id", $id);
        $statement->execute();

        return $statement->fetchAll();
    }

    static function updateFavorites($id, $user_id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE tvShows SET favorite = CASE WHEN favorite = false THEN true ELSE false END WHERE id = :id");
        $statement->bindParam(":id", $id);
        $statement->execute();
    }

    public static function delete($id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("DELETE FROM tvShows WHERE id = :id");
        $statement->bindParam(":id", $id);
        $statement->execute();
    }

    public static function getByIdForUser($user_id, $title) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT * FROM tvShows WHERE title = :title AND user_id = :user_id");
        $statement->bindParam(":title", $title);
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();

        return $statement->fetch();
    }

}