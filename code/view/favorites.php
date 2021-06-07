<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= ASSETS_URL . "style.css" ?>">
<meta carset="UTF-8" />
<title>Favorites</title>


<h1 class="pageTitle">Favorites</h1>
<?php include("view/menu.php"); ?>
<form class="search" action="<?= BASE_URL . "search" ?>" method="get">
    <input type="text" name="query" autocomplete="off" required placeholder="Search.." />
    <button>Search</button>
</form>


<div id="main">
    <div id="fav">
    <div>
        <h1>Movies</h1>
    </div>
    <div class="imgContent">
        <?php foreach ($movies as $movie): ?>
            
            <form action="<?= BASE_URL . "movie/description" ?>" method="post">
                <input type="hidden" name="movie_id" value="<?= $movie["id"] ?>" />
                <a href="javascript:{}" onclick="this.closest('form').submit();return false;"><img src="<?= $movie["img_url"] ?>" alt="<?= $movie["title"] ?>"/></a>
                <p><?= $movie["title"] ?><br/><br/>
                <?php if (!is_null($movie["year"])): ?>
                     <?= "(" . $movie["year"] . ")" ?>
                <?php endif; ?></p>
            </form>
        <?php endforeach; ?>
    </div>

    <div>
        <h1>Tv-Shows</h1>
    </div>
    <div class="imgContent">
        <?php foreach ($tvShows as $tvShow): ?>
            
            <form action="<?= BASE_URL . "tvShow/description" ?>" method="post">
                <input type="hidden" name="tvShow_id" value="<?= $tvShow["id"] ?>" />
                <a href="javascript:{}" onclick="this.closest('form').submit();return false;"><img src="<?= $tvShow["img_url"] ?>" alt="<?= $tvShow["title"] ?>"/></a>
                <p><?= $tvShow["title"] ?><br/><br/>
                <?php if (!is_null($tvShow["year"])): ?>
                     <?= "(" . $tvShow["year"] . ")" ?>
                <?php endif; ?></p>
            </form>
        <?php endforeach; ?>
    </div>

                </div>
</div>


<footer>
    <div id="footerArea">
        <h3>Movies/Tv-Shows Library</h3>
        <p>Add new Movies and Tv-Shows and keep track of what you have watched.</p>
    </div>
</footer>