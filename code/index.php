<?php

session_start();

require_once("controller/Controller.php");
require_once("controller/UserController.php");
require_once("ViewHelper.php");

define("BASE_URL", $_SERVER["SCRIPT_NAME"] . "/");
define("ASSETS_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "assets/");
define("USER", "");

$path = isset($_SERVER["PATH_INFO"]) ? trim($_SERVER["PATH_INFO"], "/") : "";

$urls = [
    "home" => function() {
        Controller::index();
    },
    "search" => function() {
        Controller::search();
    },
    "api/search" => function() {
        Controller::searchApi();
    },
    "movies" => function() {
        Controller::movies();
    },
    "add/movie" => function() {
        Controller::addMovie();  
    },
    "tvShows" => function() {
        Controller::tvShows();
    },
    "add/tvShow" => function() {
        Controller::addTvShow();
    },
    "tvShow/edit" => function() {
        Controller::editWatched();
    },
    "movie/delete" => function() {
        Controller::deleteMovie();
    },
    "tvShow/delete" => function() {
        Controller::deleteTvShow();
    },
    "tvShow/add-episode" => function() {
        Controller::addEpisode();
    },
    "tvShow/remove-episode" => function() {
        Controller::removeEpisode();
    },
    "user/favorites" => function() {
        Controller::favorites();  
    },
    "favorites/movie-update" => function() {
        Controller::updateMovieFavorites();
    },
    "favorites/tvShow-update" => function() {
        Controller::updatetvShowFavorites();
    },
    "movie/description" => function() {
        Controller::movieDescription();
    },
    "tvShow/description" => function() {
        Controller::tvShowDescription();
    },
    "update/movie-description" => function() {
        Controller::updateMovieDescription();
    },
    "update/tvShow-description" => function() {
        Controller::updateTvShowDescription();
    },
    "update/movie-title" => function() {
        Controller::updateMovieTitle();
    },
    "update/tvShow-title" => function() {
        Controller::updateTvShowTitle();
    },
    "update/movie-year" => function() {
        Controller::updateMovieYear();
    },
    "update/tvShow-year" => function() {
        Controller::updateTvShowYear();
    },
    "user/login" => function() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            UserController::login();
        } else {
            UserController::showLoginForm();
        }
    },
    "user/logout" => function() {
        UserController::logout();
    },
    "user/register" => function() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            UserController::register();
        } else {
            UserController::showRegisterForm();
        }
    },
    "" => function() {
        ViewHelper::redirect(BASE_URL . "home");
    },
];

try {
    if (isset($urls[$path])) {
        $urls[$path]();
    } else {
        echo "No controller for '$path'";
    }
} catch (Exception $e) {
    echo "An error occured: <pre>$e</pre>";
}

