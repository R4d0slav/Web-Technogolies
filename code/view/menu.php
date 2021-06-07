<div id="menu">
    <a href="<?= BASE_URL . "home" ?>">Home</a>
    <a href="<?= BASE_URL . "movies" ?>">Movies</a>
    <a href="<?= BASE_URL . "tvShows" ?>">Tv-Shows</a>
    <?php if (empty($_SESSION["user"])): ?>
        <a href="<?= BASE_URL . "user/login" ?>">Log-in</a> 
    <?php else: ?>
        <a href="<?= BASE_URL . "user/favorites" ?>">Favorites</a>
        <a href="<?= BASE_URL . "user/logout" ?>">Log-out</a>
    <?php endif; ?>
    <?php if (empty($_SESSION["user"])): ?>
        <a href="<?= BASE_URL . "user/register" ?>">Register</a>
    <?php endif; ?>
</div>