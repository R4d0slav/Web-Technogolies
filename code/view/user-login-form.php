<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= ASSETS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Login form</title>

<h1 class="pageTitle">Log-in <?= $_SESSION["user"]; ?></h1>
<?php include("view/menu.php"); ?>
<form class="search" action="<?= BASE_URL . "home" ?>" method="get">
    <input type="text" name="query" autocomplete="off" required placeholder="Search.." />
    <button>Search</button>
</form>

<div id="main">
    <form id="loginForm" action="<?= BASE_URL . "user/login" ?>" method="post">
        <h2>Log-in form</h2>
        <?php if (!empty($errorMessage)): ?>
            <p class="important"><?= $errorMessage ?></p>
        <?php endif; ?>
        <p>
            <label>Username: <input type="text" name="username" autocomplete="off" required autofocus /></label>
        </p>
        <p>
            <label>Password: <input type="password" name="password" required /></label>
        </p>
        <p><button>Log-in</button></p>
    </form>
</div>


<footer>
    <div id="footerArea">
        <h3>Movies/Tv-Shows Library</h3>
        <p>Add new Movies and Tv-Shows and keep track of what you have watched.</p>
    </div>
</footer>