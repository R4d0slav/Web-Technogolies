<?php

require_once "DBInit.php";

Class ContentDB {

    public static function init($user_id, $tvShow_id, $tvshow_id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("INSERT INTO content (season, episode, watched, tvShow_id, user_id) SELECT season, episode, watched, :tvShow_id AS tvShow_id, :user_id as user_id from content WHERE user_id IS NULL AND tvShow_id = :tvshow_id");
        $statement->bindParam(":user_id", $user_id);
        $statement->bindParam(":tvShow_id", $tvShow_id);
        $statement->bindParam(":tvshow_id", $tvshow_id);
        $statement->execute();
    }

    public static function delete($tvShow_id) {
        $db = DBInit::getInstance();
        $statement = $db->prepare("DELETE FROM content WHERE tvShow_id = :tvShow_id");
        $statement->bindParam(":tvShow_id", $tvShow_id);
        $statement->execute();

    }

    public static function getEpisodeForSeasonDefault($season, $id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT * FROM content WHERE tvShow_id = :id AND season = :season AND user_id IS NULL");
        $statement->bindParam(":id", $id);
        $statement->bindParam(":season", $season);
        $statement->execute();

        return $statement->fetchAll();
    }

    public static function getEpisodeForSeasonUser($user_id, $season, $id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT * FROM content WHERE tvShow_id = :id AND season = :season AND user_id = :user_id");
        $statement->bindParam(":id", $id);
        $statement->bindParam(":season", $season);
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();

        return $statement->fetchAll();
    }

    public static function getMaxSeasonDefault($id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT MAX(season) as max_season FROM content WHERE tvShow_id = :id AND user_id IS NULL");
        $statement->bindParam(":id", $id);
        $statement->execute();

        return $statement->fetch();
    }

    public static function getMaxSeasonUser($user_id, $id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT MAX(season) as max_season FROM content WHERE tvShow_id = :id AND user_id = :user_id");
        $statement->bindParam(":id", $id);
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();

        return $statement->fetch();
    }

    public static function updateWatchedForUser($user_id, $id, $episode) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE content SET watched = NOT watched WHERE tvShow_id = :id AND episode = :episode AND user_id = :user_id");
        $statement->bindParam(":id", $id);
        $statement->bindParam(":episode", $episode);
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();
    }

    public static function add($user_id, $tvShow_id, $season, $episode) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("INSERT INTO content (season, episode, tvShow_id, user_id) VALUES (:season, :episode, :tvShow_id, :user_id)");
        $statement->bindParam(":season", $season);
        $statement->bindParam(":episode", $episode);
        $statement->bindParam(":tvShow_id", $tvShow_id);
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();
    }

    public static function get($user_id, $tvShow_id, $season, $episode) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT * FROM content WHERE user_id = :user_id AND tvShow_id = :tvShow_id AND season = :season AND episode = :episode");
        $statement->bindParam(":season", $season);
        $statement->bindParam(":episode", $episode);
        $statement->bindParam(":tvShow_id", $tvShow_id);
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();

        return $statement->fetch();
    }

    public static function remove($user_id, $tvShow_id, $season, $episode) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("DELETE FROM content WHERE user_id = :user_id AND tvShow_id = :tvShow_id AND season = :season AND episode = :episode");
        $statement->bindParam(":season", $season);
        $statement->bindParam(":episode", $episode);
        $statement->bindParam(":tvShow_id", $tvShow_id);
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();
    }

}