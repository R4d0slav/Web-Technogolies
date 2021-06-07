<?php

require_once("ViewHelper.php");
require_once("model/MovieDB.php");
require_once("model/TvShowDB.php");
require_once("model/ContentDB.php");

class Controller {

    public static function index() {
        ViewHelper::render("view/home.php");
    }

    public static function search() {
        if (isset($_GET["query"])) {
            $query = $_GET["query"];
            if (empty($_SESSION["user"])) {
                $movieHits = MovieDB::searchDefault($query);
                $tvShowHits = TvShowDB::searchDefault($query);
            } else {
                $movieHits = MovieDB::searchById($_SESSION["user_id"], $query);
                $tvShowHits = TvShowDB::searchById($_SESSION["user_id"], $query);
            }
        } else {
            $query = "";
            $movieHits = [];
            $tvShowHits = [];
        }
        ViewHelper::render("view/search.php", ["movieHits" => $movieHits, "tvShowHits" => $tvShowHits, "query" => $query]);
    }

    public static function movies($data = [], $errors = []) {
        if (empty($data)) {
            if (empty($_SESSION["user_id"])) {
                $data = MovieDB::getAllDefault();
            } else {
                $data = MovieDB::getAllForUser($_SESSION["user_id"]);
            }
        }
        if (empty($errors)) {
            foreach ($data as $key => $value) {
                $errors[$key] = "";
            }
        }
        ViewHelper::render("view/movies.php", ["movies" => $data, "errors" => $errors]);
    }

    public static function addMovie() {
        $rules = [
            "title" => FILTER_SANITIZE_SPECIAL_CHARS,
            "img_url" => FILTER_SANITIZE_SPECIAL_CHARS
        ];

        $data = filter_input_array(INPUT_POST, $rules);

        MovieDB::addMovie($_SESSION["user_id"], $data["title"], $data["img_url"]);
        $movie = MovieDB::getByIdForUser($_SESSION["user_id"], $data["title"]);

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($movie);
    }

    public static function tvShows($data = [], $errors = []) {
        if (empty($data)) {
            if (empty($_SESSION["user_id"])) {
                $data = TvShowDB::getAllDefault();
            } else {
                $data = TvShowDB::getAllForUser($_SESSION["user_id"]);
            }
        }
        if (empty($errors)) {
            foreach ($data as $key => $value) {
                $errors[$key] = "";
            }
        }
        ViewHelper::render("view/tvShows.php", ["tvShows" => $data, "errors" => $errors]);
    }

    public static function addTvShow() {
        $rules = [
            "title" => FILTER_SANITIZE_SPECIAL_CHARS,
            "img_url" => FILTER_SANITIZE_SPECIAL_CHARS
        ];

        $data = filter_input_array(INPUT_POST, $rules);

        TvShowDB::addTvShow($_SESSION["user_id"], $data["title"], $data["img_url"]);
        $tvShow = TvShowDB::getByIdForUser($_SESSION["user_id"], $data["title"]);

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($tvShow);
    }

    public static function movieDescription($data = [], $errors = []) {
        if (empty($data)) {
            $data = MovieDB::getForId($_POST["movie_id"]);
        }
        if (empty($errors)) {
            foreach ($data as $key => $value) {
                $errors[$key] = "";
            }
        }
        ViewHelper::render("view/movie-description.php", ["movie" => $data, "errors" => $errors]);
    }

    public static function tvShowDescription($data = [], $errors = []) {
        if (empty($data)) {
            $data = TvShowDB::getForId($_POST["tvShow_id"]);
        }
        if (empty($errors)) {
            foreach ($data as $key => $value) {
                $errors[$key] = "";
            }
        }
        $content = [];
        if (empty($_SESSION["user"])) {
            $maxSeason = ContentDB::getMaxSeasonDefault($data["id"])["max_season"];
            for ($i=1; $i<=$maxSeason; $i++) {
                array_push($content, ContentDB::getEpisodeForSeasonDefault($i, $data["id"]));
            }
        } else {
            $maxSeason = ContentDB::getMaxSeasonUser($_SESSION["user_id"], $data["id"])["max_season"];
            for ($i=1; $i<=$maxSeason; $i++) {
                
                array_push($content, ContentDB::getEpisodeForSeasonUser($_SESSION["user_id"], $i, $data["id"]));
            }
        }
        ViewHelper::render("view/tvShow-description.php", ["tvShow" => $data, "errors" => $errors, "content" => $content]);
    }

    public static function updateMovieDescription() {
        $rules = [
            "id" => FILTER_SANITIZE_SPECIAL_CHARS,
            "data" => FILTER_SANITIZE_SPECIAL_CHARS
        ];

        $data = filter_input_array(INPUT_POST, $rules);

        MovieDB::updateDescription($data["id"], $data["data"]);
    }

    public static function updateMovieTitle() {
        $rules = [
            "id" => FILTER_SANITIZE_SPECIAL_CHARS,
            "data" => FILTER_SANITIZE_SPECIAL_CHARS
        ];

        $data = filter_input_array(INPUT_POST, $rules);

        MovieDB::updateTitle($data["id"], $data["data"]);
    }

    public static function updateTvShowDescription() {
        $rules = [
            "id" => FILTER_SANITIZE_SPECIAL_CHARS,
            "data" => FILTER_SANITIZE_SPECIAL_CHARS
        ];

        $data = filter_input_array(INPUT_POST, $rules);

        TvShowDB::updateDescription($data["id"], $data["data"]);
    }

    public static function updateTvShowTitle() {
        $rules = [
            "id" => FILTER_SANITIZE_SPECIAL_CHARS,
            "data" => FILTER_SANITIZE_SPECIAL_CHARS
        ];

        $data = filter_input_array(INPUT_POST, $rules);

        TvShowDB::updateTitle($data["id"], $data["data"]);
    }

    public static function favorites() {
        $movies = MovieDB::getAllFavorites($_SESSION["user_id"]);
        $tvShows = TvShowDB::getAllFavorites($_SESSION["user_id"]);
    
        ViewHelper::render("view/favorites.php", ["movies" => $movies, "tvShows" => $tvShows]);
    }

    public static function updateMovieYear() {
        MovieDB::updateYear($_POST["id"], $_POST["data"]);
    }

    public static function updateTvShowYear() {
        TvShowDB::updateYear($_POST["id"], $_POST["data"]);
    }

    public static function updateMovieFavorites() {
        MovieDB::updateFavorites($_POST["id"], $_SESSION["user_id"]);
    }

    public static function updateTvShowFavorites() {
        TvShowDB::updateFavorites($_POST["id"], $_SESSION["user_id"]);
    }

    public static function searchApi() {
        if (isset($_GET["query"]) && !empty($_GET["query"])) {
            if (empty($_SESSION["user"])) {
                $movies = MovieDB::searchDefault($_GET["query"]);
                $tvShows= TvShowDB::searchDefault($_GET["query"]);
            } else {
                $movies = MovieDB::searchById($_SESSION["user_id"], $_GET["query"]);
                $tvShows= TvShowDB::searchById($_SESSION["user_id"], $_GET["query"]);
            }
            $hits = ["movie" => $movies, "tvShow" => $tvShows];
        } else {
            $hits = [];
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($hits);
    }

    public static function deleteMovie() {
        MovieDB::delete($_POST["id"]);
    }

    public static function deleteTvShow() {
        ContentDB::delete($_POST["id"]);
        TvShowDB::delete($_POST["id"]);
    }

    public static function editWatched() {
        if ($_SESSION["user"]) {
            
            ContentDB::updateWatchedForUser($_SESSION["user_id"], $_POST["id"], $_POST["episode"]);
        }
    }

    public static function addEpisode() {
        $rules = [
            "season" => FILTER_SANITIZE_SPECIAL_CHARS,
            "episode" => FILTER_SANITIZE_SPECIAL_CHARS
        ];

        $data = filter_input_array(INPUT_POST, $rules);

        ContentDB::add($_SESSION["user_id"], $_POST["tvShow_id"], $data["season"], $data["episode"]);
        $episode = ContentDB::get($_SESSION["user_id"], $_POST["tvShow_id"], $data["season"], $data["episode"]);

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($episode);
    }

    public static function removeEpisode() {
        $rules = [
            "season" => FILTER_SANITIZE_SPECIAL_CHARS,
            "episode" => FILTER_SANITIZE_SPECIAL_CHARS
        ];

        $data = filter_input_array(INPUT_POST, $rules);

        ContentDB::remove($_SESSION["user_id"], $_POST["tvShow_id"], $data["season"], $data["episode"]);
    }


}



?>